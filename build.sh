#!/usr/bin/expect
TAR="$MOD_NAME.tar.gz"
SERVER="zhuayi@xiamo.cc"
SERVER_PATH="/data/www/zhuayi"

while getopts "a::b::c::" arg #选项后面的冒号表示该选项需要参数
do
        case $arg in
             a)
                #echo "a's arg:$OPTARG" #参数存在$OPTARG中
                MOD_NAME=$OPTARG
                ;;
             ?) #当有不认识的选项的时候arg为?
            echo "please input -a $app"
        exit 1
        ;;
        esac
done
if [ ! $MOD_NAME ]; then
    echo "please input -a $app"
    exit;
fi    

if [[ -d output ]];then
    rm -rf output
fi

mkdir output
mkdir -p output/app/

#cp 文件
cp -r -p core output
cp -r -p lib output
cp -r -p app/$MOD_NAME/ output/app/$MOD_NAME
#进入output目录
cd output

#删除SVN信息
find . -name ".DS_Store" -depth -exec rm {} \;
find . -name ".svn" -depth -exec rm {} \;


#删除配置信息
#rm -fr app/$MOD_NAME/conf/
#将output目录进行打包
##tar zcf $TAR ./*
##mv $TAR ../

cd ..
##rm -rf output

##mkdir output
##mv $TAR output/

#上传tar包
expect <<EOF
spawn rsync -vzrtopg --progress --delete --exclude=conf ./output/ $SERVER:$SERVER_PATH
expect "password:"
send "worinima\r"
expect eof
EOF

#删除output
rm -rf output

echo "build end"