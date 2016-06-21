#ifndef __FILE_OPERATE_H__
#define __FILE_OPERATE_H__

#include "sh_common.h"

namespace FileOperate{

    enum UPLOAD_LOG_TYPE{
        NAME_SERVER_LOG = 0,
        GS_SERVER_LOG,
        LG_SERVER_LOG,

        UNKONW_UPLOAD_LOG_TYPE
    };

    struct LogStruct{
        LogStruct() : folder_name(""),log_file_head(""),log_file_trail(""){};
        std::string folder_name;
        std::string log_file_head;
        std::string log_file_trail;

        bool operator < (const LogStruct& cmp) const{
            return this->folder_name < cmp.folder_name;
        }
    };

    typedef std::map<LogStruct,std::map<std::string,std::string>> log_directory_map;
    typedef std::map<UPLOAD_LOG_TYPE,log_directory_map> upload_log_directory_map;

    class CFileOperate
    {
    public:
        CFileOperate();
        ~CFileOperate();
        bool Init();
        void SendLog();
        void SendStackWalkerLog(std::string remote_file_name,std::string local_file_name) {};
    protected:
    private:
        UPLOAD_LOG_TYPE _GetUpLoadLogType();
        bool _Load();
        std::string _MakeLocalFilePath(const LogStruct& log_struct, std::string local_file_directory, const std::string& str_time);
        std::string _MakeRemoteFilePath(const LogStruct& log_struct, std::string remote_file_directory, const std::string& str_time);
        upload_log_directory_map m_log_directory;
    };
}

#endif