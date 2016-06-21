#include <stdlib.h>
#include <stdio.h>
#include "sh_filelog.h"
#include "curl_ftp_mgr.h"
#include "file_operate.h"

int main(int c, char **argv) 
{
    if (!CCurlFtpMgr::CreateInstance()) 
    {
        USER_LOG_ERROR("CCurlFtpMgr::CreateInstance() fail"); 
        return -1;
    }
    FileOperate::CFileOperate file_operate_handle;
    if (!file_operate_handle.Init()) {
        USER_LOG_ERROR("FileOperate::CFileOperate Init fail"); 
        return -1;    
    }
    while(1)
    {
        file_operate_handle.SendLog();
        Sleep(1000*(CCurlFtpMgr::Instance().GetLogSendTimeInterval()));
    }
    return 0;
}