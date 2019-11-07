<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>Jogo RPG</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/components/card/">
</head>
<body>

	<div class="container">

		<div class="row" id="principal" style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
		
		</div>
		
		<div id="mensagem" style="height:auto;">

		</div>
		
	</div>

</body>
</html>

<script type="text/javascript">

$(document).ready(function(){

    $.ajax({
       url: "<?php echo base_url(); ?>index.php/api/Batalha/loadChar",
       type: "POST",
       data: {},
       error: function() {
          alert('Error');
       },
       success: function(data) {
       	 	
       	 	var sHtml = '';

       		$.each(data,function(i,val){
       			
       			sHtml += '<div class="card" style="width: 18rem;">';
       			sHtml += '	<img class="card-img-top" src="'+val.imagem+'" alt="'+val.nome+'" style="height: 300px; width: 100%; display: block;">';
       			sHtml += '	 <div class="card-body">';
       			sHtml += '    <h5 class="card-title">'+val.nome+'</h5>';
				sHtml += '  </div>';
				sHtml += '  <ul class="list-group list-group-flush">';
				sHtml += '    <li class="list-group-item">Vida :  '+val.vida+' pontos</li>';
				sHtml += '    <li class="list-group-item">Força : '+val.forca+' pontos</li>';
				sHtml += '    <li class="list-group-item">Agilidade : '+val.agilidade+' pontos</li>';
				sHtml += '  </ul>';
				sHtml += '</div>';

       		});

       		$('#principal').append(sHtml);
       		$('#principal').after('</br><div class="card-body" align="center"><center><button class="btn btn-primary" name="batalhar" id="batalhar">Iniciar Batalha</a></center></div>');

			$("#batalhar").click(function (event) {
			   $.ajax({
		           url: '<?php echo base_url(); ?>index.php/api/Batalha/rodada',
		           type: 'POST',
		           data: {},
		           error: function() {
		           		alert("Erro ao executar jogo, contate ao administrador.")
		           },
		           success: function(data) {

		           	 	$.each(data,function(i,texto){
							(function (i) {
		           	 			$('#batalhar').attr("disabled", true);
								setTimeout(function () {
								   $('#mensagem').append('<p class="text-center">'+texto+'</p>');
								   	var target = $('.container');
									console.log(target.height());
								    $(document).scrollTop(target.height());
								}, 1500*i);
								
							
							   
							})(i);
		           	 			
						});
		           }
		        });
			});
       }	
    });

	  
});

</script>