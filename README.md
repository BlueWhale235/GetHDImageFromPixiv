# Pixiv redownload tool

此工具可以用来重新下载pixiv图片。假如以前你在浏览图片时没有查看大图而直接选择右键保存，你会保存下分辨率相对低的图片版本（如xxx_px_master1200.jpg），此工具可以保存相对分辨率高的图片版本（如xxx_px.jpg）。

# How to use
1. 获取工具 `git clone https://github.com/BuleWhale/pixiv_master1200_to_origin/`
2. 安装依赖 `composer install`
3. 在浏览器中用F12工具抓取 `COOKIE` 至 `defined.php` 的 `_COOKIE` 中
4. 把 `xxx_px_master1200` 图片放在 `testdir` 中（代码中可更改）
5. 运行 `main.php`

# Notice
* 需要php拓展：`fileinfo`  
* 测试服务器信息：`Ubuntu 20.04.4 LTS x86_64`, `Apache`, `php8.0`

# Include Libraries
* [guzzle/guzzle](https://github.com/guzzle/guzzle/)
* [filp/whoops](https://github.com/filp/whoops)

# License
GPL V3
