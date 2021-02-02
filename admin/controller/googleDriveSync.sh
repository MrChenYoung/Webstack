#!/bin/bash

#rclone sync /www/wwwroot/res.yycode.ml/backup BackupData:
#rclone sync /www/wwwroot/res.yycode.ml/loveTheater LoveTheater:
#rclone sync /www/wwwroot/res.yycode.ml/nextcloud NextCloud:
#rclone sync /www/wwwroot/res.yycode.ml/test1 BackupData:
#rclone sync /www/wwwroot/res.yycode.ml/test2 LoveTheater:
rclone sync /www/wwwroot/res.yycode.ml/test2 LoveTheater:
echo 12345 >> /www/wwwroot/res.yycode.ml/test1/test.txt