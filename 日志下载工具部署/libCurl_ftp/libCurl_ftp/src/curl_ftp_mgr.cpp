#include "curl_ftp_mgr.h"
#include "sh_filelog.h"
#include "MarkupSTL.h"

#pragma comment(lib, "WS2_32.lib")
#pragma comment(lib, "Wldap32.lib")


/* parse headers for Content-Length */
size_t getcontentlengthfunc(void *ptr, size_t size, size_t nmemb, void *stream) 
{
    int r;
    long len = 0;
    /* _snscanf() is Win32 specific */
    //r = _snscanf(ptr, size * nmemb, "Content-Length: %ld\n", &len);
    r = sscanf((const char*)ptr, "Content-Length: %ld\n", &len);
    if (r) /* Microsoft: we don't read the specs */
        *((long *) stream) = len;
    return size * nmemb;
}
/* discard downloaded data */
size_t discardfunc(void *ptr, size_t size, size_t nmemb, void *stream) 
{
    return size * nmemb;
}
//write data to upload
size_t writefunc(void *ptr, size_t size, size_t nmemb, void *stream)
{
    return fwrite(ptr, size, nmemb, (FILE*)stream);
}
/* read data to upload */
size_t readfunc(void *ptr, size_t size, size_t nmemb, void *stream)
{
    FILE *f = (FILE*)stream;
    size_t n;
    if (ferror(f))
        return CURL_READFUNC_ABORT;
    n = fread(ptr, size, nmemb, f) * size;
    return n;
}

CCurlFtpMgr::CCurlFtpMgr()
{
    m_curlhandle = NULL;
}

CCurlFtpMgr::~CCurlFtpMgr()
{
    UnInit();
}

bool CCurlFtpMgr::_Load()
{
    char szPath[255];
    snprintf(szPath, sizeof(szPath)-1, "%s/config/ftp_config.xml", share::GetCurrentExeDirectory());

    CMarkupSTL oxml;
    if (!oxml.Load(szPath) ){
        USER_LOG_ERROR("cannot load "<<szPath);
        assert(false);
        return false;
    }

    if (!oxml.FindElem("root") ){
        USER_LOG_ERROR("can not find root node "<<szPath);
        assert(false);
        return false;
    }

    if (!oxml.IntoElem()){
        USER_LOG_ERROR("can not into root node "<<szPath);
        assert(false);
        return false;
    }

    if (!oxml.FindElem("ftp_host") ){
        USER_LOG_ERROR("can not find ftp_host node "<<szPath);
        assert(false);
        return false;
    }
    m_ftp_host = oxml.GetAttrib("value");

    if (!oxml.FindElem("ftp_port") ){
        USER_LOG_ERROR("can not find ftp_port node "<<szPath);
        assert(false);
        return false;
    }
    m_ftp_port = oxml.GetAttrib("value");

    if (!oxml.FindElem("user_name") ){
        USER_LOG_ERROR("can not find user_name node "<<szPath);
        assert(false);
        return false;
    }
    m_ftp_user_name = oxml.GetAttrib("value");

    if (!oxml.FindElem("user_password") ){
        USER_LOG_ERROR("can not find user_password node "<<szPath);
        assert(false);
        return false;
    }
    m_ftp_user_password = oxml.GetAttrib("value");

    if (!oxml.FindElem("time_out") ){
        USER_LOG_ERROR("can not find time_out node "<<szPath);
        assert(false);
        return false;
    }
    m_time_out = share::a2u(oxml.GetAttrib("value"));

    if (!oxml.FindElem("try_times") ){
        USER_LOG_ERROR("can not find try_times node "<<szPath);
        assert(false);
        return false;
    }
    m_try_times = share::a2u(oxml.GetAttrib("value"));

    if (!oxml.FindElem("is_compress") ){
        USER_LOG_ERROR("can not find is_compress node "<<szPath);
        assert(false);
        return false;
    }
    if (share::a2u(oxml.GetAttrib("value")) == 0) {
        m_is_compress = false;
    } else {
        m_is_compress = true;
    }

    if (!oxml.FindElem("compress_file_password") ){
        USER_LOG_ERROR("can not find compress_file_password node "<<szPath);
        assert(false);
        return false;
    }
    m_compress_file_password = oxml.GetAttrib("value");
    
    if (!oxml.FindElem("send_log_time_interval") ){
        USER_LOG_ERROR("can not find send_log_time_interval node "<<szPath);
        assert(false);
        return false;
    }
    m_send_log_time_interval = share::a2u(oxml.GetAttrib("value"));

    oxml.OutOfElem();

    sprintf(m_user_name_password,"%s:%s",m_ftp_user_name.c_str(),m_ftp_user_password.c_str());

    return true;
}

bool CCurlFtpMgr::Init()
{
    curl_global_init(CURL_GLOBAL_ALL);
    m_curlhandle = curl_easy_init();
    if (!m_curlhandle) {
        USER_LOG_ERROR("curl_easy_init failure!!!!");  
        return false;
    }
    return _Load();
}

void CCurlFtpMgr::UnInit()
{
    if (m_curlhandle) {
        curl_easy_cleanup(m_curlhandle);
        curl_global_cleanup();
    }
}

INT32 CCurlFtpMgr::UpLoad(std::string remotepath, std::string localpath)
{
    if (!m_curlhandle || remotepath.empty() || localpath.empty()) {
        return 0;
    }

    int ret_value = 0;
    std::string tmp_local_path = localpath;
    if (m_is_compress) {
        char cmd_buf[256] = {0}; 
        localpath += ".zip";
        share::SafeSprintf(cmd_buf,"%s/7z a %s %s -p%s", share::GetCurrentExeDirectory(), localpath.c_str(),tmp_local_path.c_str(),m_compress_file_password.c_str());
        ret_value = system(cmd_buf);
        if (ret_value != 0) {
            USER_LOG_ERROR("system WinRAR file error,cmd:"<<cmd_buf);
            localpath = tmp_local_path;
        } else {
            remotepath += ".zip";
        }
    }

    FILE *f;
    long uploaded_len = 0;
    CURLcode r = CURLE_GOT_NOTHING;
    int c;
    f = fopen(localpath.c_str(), "rb");
    if (f == NULL) {
        perror(NULL);
        return 0;
    }

    CURLcode err_no = CURLE_OK;
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_UPLOAD, 1L);
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_URL, remotepath.c_str());
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_USERPWD, m_user_name_password);   
    if (m_time_out)
        err_no = curl_easy_setopt(m_curlhandle, CURLOPT_FTP_RESPONSE_TIMEOUT, m_time_out);
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_HEADERFUNCTION, getcontentlengthfunc);
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_HEADERDATA, &uploaded_len);
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_WRITEFUNCTION, discardfunc);
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_READFUNCTION, readfunc);
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_READDATA, f);
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_FTPPORT, "-"); /* disable passive mode */
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_FTP_CREATE_MISSING_DIRS, 1L);
    err_no = curl_easy_setopt(m_curlhandle, CURLOPT_VERBOSE, 1L);
    for (c = 0; (r != CURLE_OK) && (c < m_try_times); c++) {
        /* are we resuming? */
        if (c) { /* yes */
            /* determine the length of the file already written */
            /*
            * With NOBODY and NOHEADER, libcurl will issue a SIZE
            * command, but the only way to retrieve the result is
            * to parse the returned Content-Length header. Thus,
            * getcontentlengthfunc(). We need discardfunc() above
            * because HEADER will dump the headers to stdout
            * without it.
            */
            err_no = curl_easy_setopt(m_curlhandle, CURLOPT_NOBODY, 1L);
            err_no = curl_easy_setopt(m_curlhandle, CURLOPT_HEADER, 1L);
            r = curl_easy_perform(m_curlhandle);
            if (r != CURLE_OK)
                continue;
            err_no = curl_easy_setopt(m_curlhandle, CURLOPT_NOBODY, 0L);
            err_no = curl_easy_setopt(m_curlhandle, CURLOPT_HEADER, 0L);
            fseek(f, uploaded_len, SEEK_SET);
            err_no = curl_easy_setopt(m_curlhandle, CURLOPT_APPEND, 1L);
        }
        else { /* no */
            err_no = curl_easy_setopt(m_curlhandle, CURLOPT_APPEND, 0L);
        }
        r = curl_easy_perform(m_curlhandle);
    }
    fclose(f);
    if (m_is_compress && (ret_value == 0)) {
        remove(localpath.c_str());
    }
    remove(tmp_local_path.c_str());
    if (r == CURLE_OK)
        return 1;
    else {
        fprintf(stderr, "%s\n", curl_easy_strerror(r));
        return 0;
    }
}
INT32 CCurlFtpMgr::DownLoad(std::string remotepath, std::string localpath)
{
    if (!m_curlhandle || remotepath.empty() || localpath.empty()) {
        return 0;
    }
    FILE *f;
    curl_off_t local_file_len = -1 ;
    long filesize =0 ;
    CURLcode r = CURLE_GOT_NOTHING;
    struct stat file_info;
    int use_resume = 0;
    //获取本地文件大小信息
    if(stat(localpath.c_str(), &file_info) == 0)
    {
        local_file_len = file_info.st_size; 
        use_resume = 1;
    }
    //追加方式打开文件，实现断点续传
    f = fopen(localpath.c_str(), "ab+");
    if (f == NULL) {
        perror(NULL);
        return 0;
    }
    curl_easy_setopt(m_curlhandle, CURLOPT_URL, remotepath.c_str());
    curl_easy_setopt(m_curlhandle, CURLOPT_USERPWD, m_user_name_password);   
    //连接超时设置
    curl_easy_setopt(m_curlhandle, CURLOPT_CONNECTTIMEOUT, m_time_out);
    //设置头处理函数
    curl_easy_setopt(m_curlhandle, CURLOPT_HEADERFUNCTION, getcontentlengthfunc);
    curl_easy_setopt(m_curlhandle, CURLOPT_HEADERDATA, &filesize);
    // 设置断点续传
    curl_easy_setopt(m_curlhandle, CURLOPT_RESUME_FROM_LARGE, use_resume?local_file_len:0);
    curl_easy_setopt(m_curlhandle, CURLOPT_WRITEFUNCTION, writefunc);
    curl_easy_setopt(m_curlhandle, CURLOPT_WRITEDATA, f);
    curl_easy_setopt(m_curlhandle, CURLOPT_NOPROGRESS, 1L);
    curl_easy_setopt(m_curlhandle, CURLOPT_VERBOSE, 1L);
    r = curl_easy_perform(m_curlhandle);
    fclose(f);
    if (r == CURLE_OK)
        return 1;
    else {
        fprintf(stderr, "%s\n", curl_easy_strerror(r));
        return 0;
    }
}

std::string CCurlFtpMgr::GetFtpHost()
{
    return m_ftp_host;
}

std::string CCurlFtpMgr::GetFtpPort()
{
    return m_ftp_port;
}

UINT32 CCurlFtpMgr::GetLogSendTimeInterval()
{
    return m_send_log_time_interval;
}