<?php
$pdo = new PDO('mysql:host=localhost:3306;dbname=urls', 'root', '');
function generate_slug(){
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $slug = '';
    for ($i = 0; $i < 8; $i++){
        $slug .= $characters[rand(0, strlen($characters)-1)];
    }
    return $slug;
}
function get_short_urls(){
    global $pdo;
    $stmt=$pdo->query('SELECT * FROM short_urls');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if(isset($_POST['long_url']) && isset($_POST['long_url'])){
    $long_url=$_POST['long_url'];
    $custom_slug=$_POST['custom_slug'];
    if($custom_slug){
        $slug=$custom_slug;
    } 
    else{
        $slug = generate_slug();
    }
    $stmt = $pdo->prepare('INSERT INTO short_urls (long_url, slug) VALUES (?, ?)');
    $stmt->execute([$long_url, $slug]);
    $short_url = 'http://' . $domain . '/' . $slug;
    header('Location: redirect.php');
}
if(isset($_GET['l'])){
    $slug=$_GET['l'];
    $stmt=$pdo->prepare('SELECT long_url FROM short_urls WHERE slug = ?');
    $stmt->execute([$slug]);
    $long_url=$stmt->fetchColumn();
    if($long_url){
        $stmt=$pdo->prepare('UPDATE short_urls SET visit_count = visit_count + 1 WHERE slug = ?');
        $stmt->execute([$slug]);
        header("Location: $long_url");
        exit();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>URL Shortener</title>
    
</head>
<body>
    <div class="background">
        <h1>URL Shortener</h1>
        <form method="post">
            <div style="margin-top: 10px;">
                <label for="long_url" class="kolom">URL Panjang:</label>
            </div>
            <input type="text" name="long_url" id="long_url"><br>
            <div style="margin-top: 10px;">
                <label for="custom_slug" class="kolom">URL Slug:</label>
            </div>
            <input type="text" name="custom_slug" id="custom_slug"><br>
            <div style="margin-top: 10px;">
                <input type="submit" name="submit" value="Buat URL Pendek" class="btn"></input>
            </div>
        </form>
        <?php if(isset($short_url)) : ?>
            <p>URL Pendek:</p>
            <a href="<?php echo $short_url; ?>" target="_blank"><?php echo $short_url; ?></a>
        <?php endif; ?>
        <h2>Daftar URL yang Telah Dipersingkat</h2>
        <ul>
            <?php foreach (get_short_urls() as $url) : ?>
                <li>
                    <a href="<?php echo '?l='.$url['slug']; ?>" target="_blank">
                        <?php echo 'https://rai.com/' . $url['slug']; ?>
                     </a>
                    (<?php echo $url['visit_count']; ?> dilihat)
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
