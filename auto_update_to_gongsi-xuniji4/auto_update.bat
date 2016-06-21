@echo off
set last_sp_date=""
set last_update_date=last_update_date.txt

::得到上次更新时间
if exist %last_update_date% for /f "tokens=1 delims= " %%i in (%last_update_date%) do set last_sp_date=%%i


set current_date=%date:~0,4%%date:~5,2%%date:~8,2%
set sp_dir_name=www
set sp_dir_name=%cd%\%sp_dir_name%

::创建www目录
if not exist %sp_dir_name% md %sp_dir_name%

::获取改动的文件，并存到b.txt
for /f "delims=" %%t in ('svn diff -r {%last_sp_date%} --summarize https://58.215.172.114:448/svn/SanGuo/trunk/hero_server/webmanager') do echo %%t >> b.txt

if exist b.txt (
	
    setlocal enabledelayedexpansion
    ::假设目录最深16层，如果有更深层的目录则还需修改
    for /f "tokens=2 delims= " %%s in (b.txt) do (	
        set s=%%s
        set var=!s:/=;!
        set num=0
        set dir_name=%sp_dir_name%

        for %%s in ('!var!') do (set /a num+=1)

        if 8 equ !num! (
            for /f "tokens=8 delims=/" %%a in ("%%s") do (
                echo %%a|findstr ".*" > nul
                if !errorlevel! equ 0 (
                    svn export %%s !dir_name! --force
                ) else (
                    set dir_name=!dir_name!\%%a 
                    if not exist !dir_name! (md !dir_name!)
                )
            )
        )
        if 9 equ !num! (
            for /f "tokens=8,9 delims=/" %%a in ("%%s") do (
                set dir_name=!dir_name!\%%a
                if not exist !dir_name! (md !dir_name!)
                echo %%b|findstr ".*" > nul
                if !errorlevel! equ 0 (
                    svn export %%s !dir_name! --force
                ) else (
                    set dir_name=!dir_name!\%%b
                    if not exist !dir_name! (md !dir_name!)
                )
            )	
        )
        if 10 equ !num! (
            for /f "tokens=8,9,10 delims=/" %%a in ("%%s") do (
                set dir_name=!dir_name!\%%a
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%b
                if not exist !dir_name! (md !dir_name!)
                echo %%c|findstr ".*" > nul
                if !errorlevel! equ 0 (
                    svn export %%s !dir_name! --force
                ) else (
                    set dir_name=!dir_name!\%%c
                    if not exist !dir_name! (md !dir_name!)
                )
            )	
        )
        if 11 equ !num! (
            for /f "tokens=8,9,10,11 delims=/" %%a in ("%%s") do (
                set dir_name=!dir_name!\%%a
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%b
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%c
                if not exist !dir_name! (md !dir_name!)
                echo %%d|findstr ".*" > nul
                if !errorlevel! equ 0 (
                    svn export %%s !dir_name! --force
                ) else (
                    set dir_name=!dir_name!\%%d
                    if not exist !dir_name! (md !dir_name!)
                )
            )	
        )
        if 12 equ !num! (
            for /f "tokens=8,9,10,11,12 delims=/" %%a in ("%%s") do (
                set dir_name=!dir_name!\%%a
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%b
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%c
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%d
                if not exist !dir_name! (md !dir_name!)
                echo %%d|findstr ".*" > nul
                if !errorlevel! equ 0 (
                    svn export %%s !dir_name! --force
                ) else (
                    set dir_name=!dir_name!\%%e
                    if not exist !dir_name! (md !dir_name!)
                )
            )	
        )
        if 13 equ !num! (
            for /f "tokens=8,9,10,11,12,13 delims=/" %%a in ("%%s") do (
                set dir_name=!dir_name!\%%a
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%b
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%c
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%d
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%e
                if not exist !dir_name! (md !dir_name!)
                echo %%d|findstr ".*" > nul
                if !errorlevel! equ 0 (
                    svn export %%s !dir_name! --force
                ) else (
                    set dir_name=!dir_name!\%%f
                    if not exist !dir_name! (md !dir_name!)
                )
            )	
        )
        if 14 equ !num! (
            for /f "tokens=8,9,10,11,12,13,14 delims=/" %%a in ("%%s") do (
                set dir_name=!dir_name!\%%a
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%b
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%c
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%d
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%e
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%f
                if not exist !dir_name! (md !dir_name!)
                echo %%d|findstr ".*" > nul
                if !errorlevel! equ 0 (
                    svn export %%s !dir_name! --force
                ) else (
                    set dir_name=!dir_name!\%%g
                    if not exist !dir_name! (md !dir_name!)
                )
            )	
        )
        if 15 equ !num! (
            for /f "tokens=8,9,10,11,12,13,15 delims=/" %%a in ("%%s") do (
                set dir_name=!dir_name!\%%a
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%b
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%c
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%d
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%e
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%f
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%g
                if not exist !dir_name! (md !dir_name!)
                echo %%d|findstr ".*" > nul
                if !errorlevel! equ 0 (
                    svn export %%s !dir_name! --force
                ) else (
                    set dir_name=!dir_name!\%%h
                    if not exist !dir_name! (md !dir_name!)
                )
            )	
        )
        if 16 equ !num! (
            for /f "tokens=8,9,10,11,12,13,15,16 delims=/" %%a in ("%%s") do (
                set dir_name=!dir_name!\%%a
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%b
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%c
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%d
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%e
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%f
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%g
                if not exist !dir_name! (md !dir_name!)
                set dir_name=!dir_name!\%%h
                if not exist !dir_name! (md !dir_name!)
                echo %%d|findstr ".*" > nul
                if !errorlevel! equ 0 (
                    svn export %%s !dir_name! --force
                ) else (
                    set dir_name=!dir_name!\%%i
                    if not exist !dir_name! (md !dir_name!)
                )
            )	
        )	
    ) 
) else (
    echo "no files to update....."
    pause
    if exist %sp_dir_name% (
        rd /s /q %sp_dir_name%
    )
    exit
)

::拷贝到gongsi-xuniji4
net use \\gongsi-xuniji4\ipc$ 123456/user:administrator
xcopy %sp_dir_name%\* \\gongsi-xuniji4\www\ /E /Y

::记录当前更新日期
set /a next_sp_number=next_sp_number+1
echo %date:~0,4%-%date:~5,2%-%date:~8,2%>%last_update_date%

echo "ready to delelet dir www"
pause

::删除临时文件
if exist b.txt ( del /q /s b.txt ) else ( echo "svn diff error!!!" )

if exist %sp_dir_name% (
	rd /s /q %sp_dir_name%
)	

pause

