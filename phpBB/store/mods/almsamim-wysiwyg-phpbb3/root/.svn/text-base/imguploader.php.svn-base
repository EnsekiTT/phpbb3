<?php
class ImageShack {

static function mimeType($filename)
{
    $ext = pathinfo($filename);
    $ext = $ext['extension'];

    switch($ext)
    {
        case "bmp":
            return "image/bmp";
            break;
        case "gif":
            return "image/gif";
            break;
        case "jpe":
            return "image/jpeg";
            break;
        case "jpeg":
            return "image/jpeg";
            break;
        case "jpg":
            return "image/jpeg";
            break;
        case "png":
            return "image/png";
            break;
        case "swf":
            return "application/x-shockwave-flash";
            break;
        case "tif":
            return "image/tiff";
            break;
        case "tiff":
            return "image/tiff";
            break;
        default:
            return "";
            break;
    }
}
static function upload($file)
{
	$filename = $file['name'];
   	$binarydata = file_get_contents($file['tmp_name']);

    $sock = @fsockopen("www.imageshack.us", 80, $errno, $errstr, 30);

    $mimetype = self::mimeType($filename);

    $AaB03x = "--AaB03x\r\n";
    $AaB03x .= "content-disposition: form-data; name=\"uploadtype\"\r\n\r\n";

    $AaB03x .= "on\r\n";
    $AaB03x .= "--AaB03x\r\n";
    $AaB03x .= "content-disposition: form-data; name=\"fileupload\"; filename=\"".basename($filename)."\"\r\n";
    $AaB03x .= "Content-Type: $mimetype\r\n";
    $AaB03x .= "Content-Transfer-Encoding: binary\r\n\r\n";

    $AaB03x .= "$binarydata\r\n";
    $AaB03x .= "--AaB03x--\r\n";

    $header = "POST / HTTP/1.1\r\n";
    $header .= "Host: www.imageshack.us\r\n";
    $header .= "Content-type: multipart/form-data, boundary=AaB03x\r\n";
    $header .= "Content-Length: ".strlen($AaB03x)."\r\n\r\n";

    $header .= $AaB03x;

    fwrite($sock, $header);
	
	$response = '';

    while (!feof($sock))
    {
        $response .= fgets($sock, 128);
    }

    fclose($sock);

    preg_match("/location: http:\/\/img\d{1,3}\.imageshack\.us\/content\.php\?page=done&l=(img\d{1,3})\/(\d{1,4})\/(\w+.\w+)/i", $response, $matches);

    return "{$matches[1]}.imageshack.us/{$matches[1]}/{$matches[2]}/{$matches[3]}";
}
}
$submit	= (isset($_POST['submit'])) ? true : false;
	if ($submit) {
	$URL = ImageShack::upload($_FILES['image']);
	echo " <script type=\"text/javascript\">
	function insertimage(){
	        if (parent.comm._viewmode == 1){
                parent.comm._HTML(' ',' ',' <img src=\"http://" . $URL ."\" />');
                }else{
                parent.bbfontstyle('[img]http://" . $URL ."','[/img]');
                }
				}
				insertimage();
				</script>" ;
	echo '<form method="post" enctype="multipart/form-data"><div>
                <span>Upload image</span>
                <input type="file" name="image"><input type="submit" name="submit" value="upload">
            </div>
        </form>
';
	}else{
	echo '<form method="post" enctype="multipart/form-data"><div>
                <span>Upload image</span>
                <input type="file" name="image"><input type="submit" name="submit" value="upload">
            </div>
        </form>
';
	}
