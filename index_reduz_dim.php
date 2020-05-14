<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.slidecontainer {
  width: 100%;
}
.slider {
  -webkit-appearance: none;
  width: 100%;
  height: 15px;
  border-radius: 5px;   
  background: #d3d3d3;
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s;
  transition: opacity .2s;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 25px;
  border-radius: 50%; 
  background: #4CAF50;
  cursor: pointer;
}

.slider::-moz-range-thumb {
  width: 25px;
  height: 25px;
  border-radius: 50%;
  background: #4CAF50;
  cursor: pointer;
}
</style>
</head>
<body>
<div class="container">
	<form method="POST">
		<h1>Range Slider Picture</h1>

		<div class="slidecontainer">
		  <input type="range" min="1" max="100" value="50" class="slider" id="myRange">
		  <p>Value: <span id="demo"></span></p>
		  <input type="text" id="valor" name="valor" value="50">
		</div>
		
		<input type="submit">

		<script>
		var slider = document.getElementById("myRange");
		var output = document.getElementById("demo");
		output.innerHTML = slider.value;

		slider.oninput = function() {
		  output.innerHTML = this.value;
		  document.getElementById("valor").value=this.value;
		}
		</script>
		</div>
	</form>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>	

<?php
error_reporting(0);
$total=0;
$convertidos=0;
$valor=1;
if(isset($_POST['valor']))
	$valor=$_POST['valor'];
//Primeiro setamos as variáveis

//Tamanho da thumb, um valor inteiro, que corresponde à porcentagem.
$Tamanho = $valor;

//Diretório onde estão as imagens
$Fotos = "./teste/";

//Diretório onde serão criadas as Thumbs
$Thumbs = "thumbs/";

//Seta qual tipo de arquivo será usado, no caso Jpg, Gif ou PNG
$Ext = ".jpg";

//Seta a qualidade da Thumb
$Qualidade = 95;

//Vamos abrir o diretório das imagens
$dh = opendir(($dir = "$Fotos"));

$ifc=0;
$ifnc=0;
$post=0;
$contaX=0;
$contaY=0;
$contaHE=0;
$contaWI=0;

//Agora vamos varrer todo o diretório à procura das imagens
while (false !== ($filename = readdir($dh))) {
    //Verificamos se o arquivo é uma imagem de extensão igual á setada em $Ext
    if (strtoupper(substr($filename,-4)) != strtoupper($Ext)) {
        continue;
    }
	$fotosConvertidas[$ifc]=$filename;
	$fotosConvertidasTam[$ifc++]=filesize($dir.$filename);
	
    //Verificamos aqui com que tipo de imagem vai trabalhar
    if (strtoupper($Ext) == ".JPG") {
        $ExtFunc = "Jpeg";
    } elseif (strtoupper($Ext) == ".GIF") {
        $ExtFunc = "Gif";
    } elseif (strtoupper($Ext) == ".PNG") {
        $ExtFunc = "Png";
    }
    
    //Criamos a imagem apartir da extensão setada em $ExtFunc
    //Concatenamos o valor de $ExtFunc para termos a função que criará a imagem
    //Podendo ser "ImageCreateFromJpeg" , "ImageCreateFromGif" ou "ImageCreateFromPng"
    
    $CriarImagemDe = "ImageCreateFrom" . $ExtFunc;
    $img = $CriarImagemDe($dir . $filename);
    
    //Aqui tiramos a proporção , o tamanho da thumb em relação à imagem

    //Pega largura da imagem
    $he = ImageSX($img);
	$_he[$contaHE++]=$he;
	
    //Pega altura da imagem
    $wi = ImageSY($img);
	$_wi[$contaWI++]=$wi;

    //Seta valor da largura da thumb
    $x = ($he / 100) * $Tamanho;
	$_x[$contaX++]=$x;
    //Seta valor da altura da thumb
    $y = ($wi / 100) * $Tamanho;
    $_y[$contaY++]=$y;    
    //Aqui é criada a nova imagem, a thumb
    $img_nova = imagecreatetruecolor($x,$y); 
    
    //Agora a nova imagem é redimencionada
    $k=imagecopyresampled($img_nova, $img, 0, 0, 0, 0, $x, $y, $he, $wi); 
	
	if(!$k){
		$convertidos=$convertidos+1;
		echo "A imagem ".$filename." não foi convertida<br>";
		$fotosNConvertidas[$ifnc]=$filename;
		$fotosNConvertidasTam[$ifnc++]=filesize($dir.$filename);
	}
	$total=$total+1;
	
    //Agora salvamos a Thumb no diretório especificado em $Thumbs, com a qualidade setada em $Qualidade
    //Para salvar a nova imagem, usaremos a função correspondente à extensão 
    //Que pode ser "ImageJpeg" , "ImageGif" ou "ImagePng" , concatenando os valores Image + $ExtFunc
    $Image = "Image" . $ExtFunc;
    $Image($img_nova, $Thumbs . $filename, $Qualidade);
	
	$arquivost[$post]=$filename;	
    $arquivostTAM[$post++]=filesize($Thumbs.$filename);
	
    //Destruimos o cache da imagem para liberar uma nova thumb
    ImageDestroy($img_nova);
    ImageDestroY($img); 
}

//Pronto todas as thumbnails foram criadas
//echo "Thumbnails Geradas com sucesso!";
echo "<div class='container'><hr>";
echo "Total de arquivos ".$total."<br>";
echo "Arquivos não convertidos ".$convertidos;

?>
<table class="table table-hover">
<?php
echo "<thead>";
echo "<tr class='table-success'><td colspan='2' align='center'>lista de arquivos de ".$Fotos."</td></tr>";
echo "<tr>";
	echo "<th scope='col' class='bg-success'>Nome</th>";
	echo "<th scope='col' class='bg-success'>Tamanho em Kilo Bytes</th>";
echo "</tr>";
echo "</thead>";	
for($i=0;$i<count($fotosConvertidas);$i++){
	echo "<tr class='table-success'>";
		echo "<td>".$fotosConvertidas[$i]."</td>";
		echo "<td>".ceil($fotosConvertidasTam[$i]/1024)." KB ".$_he[$i]."x".$_wi[$i]."</td>";
	echo "</tr>"; 
}
echo "</table>";
echo "<hr>";
echo "<br>lista de arquivos de ".$Thumbs."<br>";
echo "Nome";
echo "Tamanho<br>";

?>
<table class="table table-hover">
<?php
echo "<thead>";
echo "<tr class='table-success'><td colspan='2' align='center'>lista de arquivos de ".$Thumbs."</td></tr>";
echo "<tr>";
	echo "<th scope='col' class='bg-success'>Nome</th>";
	echo "<th scope='col' class='bg-success'>Tamanho em Kilo Bytes</th>";
echo "</tr>";
echo "</thead>";	
for($i=0;$i<count($arquivost);$i++){
	echo "<tr class='table-success'>";
		echo "<td>".$arquivost[$i]."</td>";
		echo "<td>".ceil($arquivostTAM[$i]/1024)." KB ".ceil($_x[$i])."x".ceil($_y[$i])."</td>";
	echo "</tr>"; 
}
echo "</table>"; 


if(!$k){
	echo "<hr>";
	echo "<br>lista de arquivos não convertidos ".$Thumbs."<br>";
	echo "Nome";
	echo "Tamanho<br>";
	for($i=0;$i<count($fotosNConvertidas);$i++){
		echo $fotosNConvertidas[$i];
		echo $fotosNConvertidasTam[$i]."<br>";
	}
}
?>
</div>
</body>
</html>