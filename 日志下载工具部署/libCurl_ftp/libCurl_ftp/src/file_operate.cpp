#include "file_operate.h"
#include "sh_common.h"
#include "sh_filelog.h"
#include "curl_ftp_mgr.h"


namespace FileOperate {

    CFileOperate::CFileOperate()
    {

    }
    CFileOperate::~CFileOperate()
    {

    }
    bool CFileOperate::Init()
    {
        if (!CCurlFtpMgr::Instance().Init()) {
            return false;
        }
        return _Load();   
    }
    UPLOAD_LOG_TYPE CFileOperate::_GetUpLoadLogType()
    {
        if (_access("../config/ns_config.xml",0) != -1) {
            return FileOperate::NAME_SERVER_LOG;
        }
        if (_access("../config/lg_config.xml",0) != -1) {
            return FileOperate::LG_SERVER_LOG;
        }
        if (_access("../config/gs_config.xml",0) != -1) {
            return FileOperate::GS_SERVER_LOG;
        }
        return FileOperate::UNKONW_UPLOAD_LOG_TYPE;
    }

    bool CFileOperate::_Load()
    {
        UPLOAD_LOG_TYPE upload_load_type = _GetUpLoadLogType();
        if (upload_load_type == NAME_SERVER_LOG) {
            char szPath[255];
            sprintf(szPath,"../share_config/share_log_config.xml");

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

            if (!oxml.FindElem("logtype") ){
                USER_LOG_ERROR("can not find logtype node "<<szPath);
                assert(false);
                return false;
            }

            if (!oxml.IntoElem()){
                USER_LOG_ERROR("can not into logtype node "<<szPath);
                assert(false);
                return false;
            }

            log_directory_map log_directory_map_tmp;
            while (oxml.FindElem("type")) {
                LogStruct log_struct_tmp;
                log_struct_tmp.folder_name = oxml.GetAttrib("name");
                log_struct_tmp.log_file_head = oxml.GetAttrib("log_file_head");
                log_struct_tmp.log_file_trail = oxml.GetAttrib("log_file_trail");
                if (!log_struct_tmp.folder_name.empty()) {

                    std::map<std::string,std::string> remote_local_directory;
                    std::string remote_direcotry = "ftp://";
                    remote_direcotry += CCurlFtpMgr::Instance().GetFtpHost();
                    remote_direcotry += ":";
                    remote_direcotry += CCurlFtpMgr::Instance().GetFtpPort();
                    remote_direcotry += "/";
                    remote_direcotry += "name_server/";
                    remote_direcotry += log_struct_tmp.folder_name;
                    remote_direcotry += "/";

                    std::string local_directory = "..\\";
                    local_directory += log_struct_tmp.folder_name;
                    local_directory += "\\";

                    remote_local_directory.insert(std::pair<std::string,std::string>(remote_direcotry,local_directory));
                    log_directory_map_tmp.insert(std::pair<LogStruct,std::map<std::string,std::string>>(log_struct_tmp,remote_local_directory));
                }
            }
            m_log_directory.insert(std::pair<UPLOAD_LOG_TYPE,log_directory_map>(upload_load_type,log_directory_map_tmp));
            oxml.OutOfElem();
            oxml.OutOfElem();
        } else if (upload_load_type == LG_SERVER_LOG) {
            std::string lg_id = "";
            {
                char szPath[255];
                sprintf(szPath,"../config/lg_config.xml");

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

                if (!oxml.FindElem("loginserver") ){
                    USER_LOG_ERROR("can not find loginserver node "<<szPath);
                    assert(false);
                    return false;
                }

                if (!oxml.IntoElem()){
                    USER_LOG_ERROR("can not into loginserver node "<<szPath);
                    assert(false);
                    return false;
                }
                
                if (!oxml.FindElem("id") ){
                    USER_LOG_ERROR("can not find id node "<<szPath);
                    assert(false);
                    return false;
                }

                lg_id = oxml.GetAttrib("value");
            }
            {
                char szPath[255];
                sprintf(szPath,"../share_config/share_log_config.xml");

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

                if (!oxml.FindElem("logtype") ){
                    USER_LOG_ERROR("can not find logtype node "<<szPath);
                    assert(false);
                    return false;
                }

                if (!oxml.IntoElem()){
                    USER_LOG_ERROR("can not into logtype node "<<szPath);
                    assert(false);
                    return false;
                }

                log_directory_map log_directory_map_tmp;
                while (oxml.FindElem("type")) {
                    LogStruct log_struct_tmp;
                    log_struct_tmp.folder_name = oxml.GetAttrib("name");
                    log_struct_tmp.log_file_head = oxml.GetAttrib("log_file_head");
                    log_struct_tmp.log_file_trail = oxml.GetAttrib("log_file_trail");
                    if (!log_struct_tmp.folder_name.empty()) {
                        std::map<std::string,std::string> remote_local_directory;
                        std::string remote_direcotry = "ftp://";
                        remote_direcotry += CCurlFtpMgr::Instance().GetFtpHost();
                        remote_direcotry += ":";
                        remote_direcotry += CCurlFtpMgr::Instance().GetFtpPort();
                        remote_direcotry += "/";
                        remote_direcotry += "area_";
                        remote_direcotry += lg_id.substr(0,1);
                        remote_direcotry += "/";
                        remote_direcotry += "lg_";
                        remote_direcotry += lg_id;
                        remote_direcotry += "/";
                        remote_direcotry += log_struct_tmp.folder_name;
                        remote_direcotry += "/";

                        std::string local_directory = "..\\";
                        local_directory += log_struct_tmp.folder_name;
                        local_directory += "\\";

                        remote_local_directory.insert(std::pair<std::string,std::string>(remote_direcotry,local_directory));
                        log_directory_map_tmp.insert(std::pair<LogStruct,std::map<std::string,std::string>>(log_struct_tmp,remote_local_directory));
                    }
                }
                m_log_directory.insert(std::pair<UPLOAD_LOG_TYPE,log_directory_map>(upload_load_type,log_directory_map_tmp));
                oxml.OutOfElem();
                oxml.OutOfElem();
            }
        } else if (upload_load_type == GS_SERVER_LOG) {
            std::string gs_id = "";
            {
                char szPath[255];
                sprintf(szPath,"../config/gs_config.xml");

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

                if (!oxml.FindElem("gameserver") ){
                    USER_LOG_ERROR("can not find gameserver node "<<szPath);
                    assert(false);
                    return false;
                }

                if (!oxml.IntoElem()){
                    USER_LOG_ERROR("can not into gameserver node "<<szPath);
                    assert(false);
                    return false;
                }

                if (!oxml.FindElem("id") ){
                    USER_LOG_ERROR("can not find id node "<<szPath);
                    assert(false);
                    return false;
                }

                gs_id = oxml.GetAttrib("value");
            }
            {
                char szPath[255];
                sprintf(szPath,"../share_config/share_log_config.xml");

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

                if (!oxml.FindElem("logtype") ){
                    USER_LOG_ERROR("can not find logtype node "<<szPath);
                    assert(false);
                    return false;
                }

                if (!oxml.IntoElem()){
                    USER_LOG_ERROR("can not into logtype node "<<szPath);
                    assert(false);
                    return false;
                }

                log_directory_map log_directory_map_tmp;
                while (oxml.FindElem("type")) { 
                    LogStruct log_struct_tmp;
                    log_struct_tmp.folder_name = oxml.GetAttrib("name");
                    log_struct_tmp.log_file_head = oxml.GetAttrib("log_file_head");
                    log_struct_tmp.log_file_trail = oxml.GetAttrib("log_file_trail");
                    if (!log_struct_tmp.folder_name.empty()) {
                        std::map<std::string,std::string> remote_local_directory;
                        std::string remote_direcotry = "ftp://";
                        remote_direcotry += CCurlFtpMgr::Instance().GetFtpHost();
                        remote_direcotry += ":";
                        remote_direcotry += CCurlFtpMgr::Instance().GetFtpPort();
                        remote_direcotry += "/";
                        remote_direcotry += "area_";
                        remote_direcotry += gs_id.substr(0,1);
                        remote_direcotry += "/";
                        remote_direcotry += "gs_";
                        remote_direcotry += gs_id;
                        remote_direcotry += "/";
                        remote_direcotry += log_struct_tmp.folder_name;
                        remote_direcotry += "/";

                        std::string local_directory = "..\\";
                        local_directory += log_struct_tmp.folder_name;
                        local_directory += "\\";

                        remote_local_directory.insert(std::pair<std::string,std::string>(remote_direcotry,local_directory));
                        log_directory_map_tmp.insert(std::pair<LogStruct,std::map<std::string,std::string>>(log_struct_tmp,remote_local_directory));
                    }
                }
                m_log_directory.insert(std::pair<UPLOAD_LOG_TYPE,log_directory_map>(upload_load_type,log_directory_map_tmp));
                oxml.OutOfElem();
                oxml.OutOfElem();
            }
        }
        return true;
    }
    
    void CFileOperate::SendLog()
    {
        //获取当前年月日，格式xxxx-xx-xx
        std::string str_time = share::time2datestr(time(NULL));
        upload_log_directory_map::const_iterator it_uldm = m_log_directory.begin();
        for (; it_uldm != m_log_directory.end(); ++it_uldm) {
            log_directory_map ldm = it_uldm->second;

            if (!ldm.empty()) {
                log_directory_map::const_iterator it_ldm = ldm.begin();
                for (; it_ldm != ldm.end(); ++it_ldm) {
                    LogStruct log_struct_tmp = it_ldm->first;
                    std::map<std::string,std::string> remote_local_directory_map = it_ldm->second;
                    std::map<std::string,std::string>::const_iterator it_rldm = remote_local_directory_map.begin();
                    for (; it_rldm != remote_local_directory_map.end(); ++it_rldm) {
                        std::string local_file_path = _MakeLocalFilePath(log_struct_tmp,it_rldm->second,str_time);
                        if (!local_file_path.empty()) {
                            std::string remote_file_path = _MakeRemoteFilePath(log_struct_tmp,it_rldm->first,str_time);
                            if (!remote_file_path.empty()) {
                                CCurlFtpMgr::Instance().UpLoad(remote_file_path.c_str(),local_file_path.c_str());
                            }
                        }
                    }
                }     
            }
        }
    }

    std::string CFileOperate::_MakeLocalFilePath(const LogStruct& log_struct, std::string local_file_directory, const std::string& str_time)
    {
        std::string ret_string = log_struct.log_file_head;
        ret_string += "_";
        ret_string += str_time;
        ret_string += log_struct.log_file_trail;
        
        local_file_directory += ret_string;

        //判断文件是否存在
        if (_access(local_file_directory.c_str(),0) == -1) {
            return "";
        }

        //由于源日志文件是打开的，压缩时会因为文件被别的进程打开而出现问题，所以得先把文件拷贝一份，
        //拷贝源文件到当前目录（当前工程)下
        char cmd_buf[256] = {0};
        share::SafeSprintf(cmd_buf,"xcopy %s /C/Y",local_file_directory.c_str());
        INT32 ret_value = system(cmd_buf);
        if (ret_value != 0) {
            USER_LOG_ERROR("xcopy error,cmd_buf:%s",cmd_buf);
            return "";
        }

        return ret_string;
    }

    std::string CFileOperate::_MakeRemoteFilePath(const LogStruct& log_struct, std::string remote_file_directory, const std::string& str_time)
    {
        if (remote_file_directory.empty()) {
            return "";
        }
        remote_file_directory += log_struct.log_file_head;
        remote_file_directory += "_";
        remote_file_directory += str_time;
        remote_file_directory += log_struct.log_file_trail;

        return remote_file_directory;

    }
}