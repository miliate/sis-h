* Sync to server

rsync -r --progress /Users/manhdx/git/sish/ qch:/var/www/sish_new/

* Login to server and check new code

cd /var/www
mv sish sish_old
mv sish_new/ sish