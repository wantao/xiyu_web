#ifndef __CURL_FTP_MGR_H__
#define __CURL_FTP_MGR_H__

#include "sh_common.h"
#include <curl/curl.h>
#include <sys/stat.h>

class CCurlFtpMgr : public share::CSingleton<CCurlFtpMgr> {
public:
    CCurlFtpMgr();
    ~CCurlFtpMgr();
    bool Init();
    void UnInit();
    INT32 UpLoad(std::string remotepath, std::string localpath);
    INT32 DownLoad(std::string remotepath, std::string localpath);
    std::string GetFtpHost();
    std::string GetFtpPort();
    UINT32 GetLogSendTimeInterval();
protected:
private:
    bool _Load();
    std::string m_ftp_host;
    std::string m_ftp_port;
    std::string m_ftp_user_name;
    std::string m_ftp_user_password;
    UINT32 m_time_out;
    UINT32 m_try_times;
    char m_user_name_password[64];
    CURL* m_curlhandle;
    bool m_is_compress;
    std::string m_compress_file_password;
    UINT32 m_send_log_time_interval;
};

#endif