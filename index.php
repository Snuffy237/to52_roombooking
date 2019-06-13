<?php
//Chargement du fichier de la base MongoDB
require_once('mongoDb/db.php');
//Traitement des données de la base de donnée  MongoDB
$query = new MongoDB\Driver\Query([]);
$events = $conn->executeQuery($dbname, $query);

//Affichage du message d'erreur si réservation déjà existante
if(isset($_GET['already'])){
  $already = $_GET['already'];

  if($already == 'true'){
    echo"<script type='text/javascript'>alert('These hours are already booked');</script>";
    //$already='false';
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Reservation de salle</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- FullCalendar -->
	<link href='css/fullcalendar.css' rel='stylesheet' />

  <!--Datetimpepicker css -->
  <link href='css/jquery.datetimepicker.min.css' rel='stylesheet' />


    <!-- Custom CSS -->
    <style>
    body {
        padding-top: 70px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
	#calendar {
		max-width: 800px;
	}
	.col-centered{
		float: none;
		margin: 0 auto;
	}
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Réservation Salle</a>
            </div>

        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div id="calendar" class="col-centered">
        </div>
    </div>
        <!-- /.row -->

		<!-- Modal -->
		<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form id="formAdd" class="form-horizontal" method="POST" action="addEvent.php">

			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Réserver</h4>
			  </div>
			  <div class="modal-body">

				  <div class="form-group">
  					<label for="name" class="col-sm-2 control-label">Nom</label>
  					<div class="col-sm-10">
  					  <input type="text" name="name" class="form-control" id="name" placeholder="Nom">
  					</div>
          </div>
          <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10">
              <input type="text" name="title" class="form-control" id="title" placeholder="Description">
            </div>
				  </div>
				  <div class="form-group">
					  <label for="color" class="col-sm-2 control-label">Service</label>
  					<div class="col-sm-10">
  					  <select name="color" class="form-control" id="color">
  						  <option value="">Selectionner</option>
                <option style="color:#008000;" value="#008000">&#9724; CIAD </option>
  						  <option style="color:#0071c5;" value="#0071c5">&#9724; UTBM</option>
  						  <option style="color:#FFD700;" value="#FFD700">&#9724; FEMTO</option>>
  						  <option style="color:#FF0000;" value="#FF0000">&#9724; Autre </option>
  						</select>
  					</div>
				  </div>
				  <div class="form-group">
					<label for="start" class="col-sm-2 control-label">Date de début</label>
					<div class="col-sm-10">
					  <input type="text" name="start" class="form-control" id="start">
					</div>
				  </div>
				  <div class="form-group">
					<label for="end" class="col-sm-2 control-label">Date de fin</label>
					<div class="col-sm-10">
					  <input type="text" name="end" class="form-control" id="end">
					</div>
				  </div>

			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				<button type="submit" class="btn btn-primary">Enregistrer</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>



		<!-- Modal for suppression and modification-->
		<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form id="formEdit" class="form-horizontal" method="POST" action="editEventTitle.php">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Modifier Réservation</h4>
			  </div>
			  <div class="modal-body">
          <div class="form-group">
					<label for="name" class="col-sm-2 control-label">Nom</label>
					<div class="col-sm-10">
					  <input type="text" name="name" class="form-control" id="name" placeholder="Nom" disabled>
					</div>
				  </div>

				  <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title" placeholder="Description">
					</div>
				  </div>
				  <div class="form-group">
					<label for="color" class="col-sm-2 control-label">Agence</label>
					<div class="col-sm-10">
            <select name="color" class="form-control" id="color">
              <option value="">Sélectionner</option>
              <option style="color:#008000;" value="#008000">&#9724; CIAD </option>
              <option style="color:#0071c5;" value="#0071c5">&#9724; UTBM</option>
              <option style="color:#FFD700;" value="#FFD700">&#9724; FEMTO</option>>
              <option style="color:#FF0000;" value="#FF0000">&#9724; Autre </option>
            </select>
					</div>
				  </div>
				    <div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
						  <div class="checkbox">
							<label class="text-danger"><input type="checkbox"  name="delete"> Supprimer</label>
						  </div>
						</div>
					</div>

				  <input type="hidden" name="id" class="form-control" id="id">


			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				<button type="submit" class="btn btn-primary">Enregistrer</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

	<!-- FullCalendar -->
	<script src='js/moment.min.js'></script>
	<script src='js/fullcalendar.min.js'></script>
  <!--script src='js/sample_french.js'></script-->
  <script src='js/jquery.datetimepicker.full.js'></script>
  <!--script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/locale/fr.js'></script-->

	<script>

	$(document).ready(function() {

    //Vérification avant l'envoi du formulaire de création de date
    $('#formAdd').on('submit', function(){
      $('.alert').remove();
      //Récupération des dates
        var dateStart = new Date($('#ModalAdd #start').val());
        var dateEnd = new Date($('#ModalAdd #end').val());
        var timeDiff = dateEnd.getTime() - dateStart.getTime();
        //Variable pour l'Heure
        var dateStartPur = $('#ModalAdd #start').val();
        var dateEndPur = $('#ModalAdd #end').val();
        console.log(dateStartPur);
        console.log(dateEndPur);
        var dateS = dateStartPur.split(' ');
        var dateE = dateEndPur.split(' ');
        console.log(dateS[1]);
        console.log(dateE[1]);

        if($('#ModalAdd #title').val() == ''){
          $('#myModalLabel').before('<div class="alert alert-danger">Indiquez une description.</div>');
          return false;
        }
        else if($('#ModalAdd #name').val() == ''){
          $('#myModalLabel').before('<div class="alert alert-danger">Indiquez un nom.</div>');
          return false;
        }
        else if($('#ModalAdd #color').val() == 0){
          $('#myModalLabel').before('<div class="alert alert-danger">Indiquez un service.</div>');
          return false;
        }
        else if(timeDiff<0){
          $('#myModalLabel').before('<div class="alert alert-danger">Entrez un créneau horaire correct.</div>');
          return false;
        }
        else if(dateE[1]==dateS[1]){
          $('#myModalLabel').before('<div class="alert alert-danger">Entrez un créneau horaire correct (Vérifiez l\'heure).</div>');
          return false;
        }

    });

    //Vérification sur le formulaire de modification
    $('#formEdit').on('submit', function(){
      $('.alert').remove();

      if($('#ModalEdit #title').val() == ''){
        $('#formEdit').before('<div class="alert alert-danger">Indiquez une description.</div>');
        return false;
      }

      if($('#ModalEdit #color').val() == 0){
        $('#formEdit').before('<div class="alert alert-danger">Indiquez un service.</div>');
        return false;
      }

    });

		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next,today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			//defaultDate: '2016-01-12',
      defaultView : 'agendaWeek',
      //lang:'fr',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			selectable: true,
			selectHelper: true,
			select: function(start, end) {

				$('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd').modal('show');
			},
			eventRender: function(event, element) {
				element.bind('dblclick', function() {
					$('#ModalEdit #id').val(event.id);
					$('#ModalEdit #title').val(event.title);
          $('#ModalEdit #name').val(event.name);
					$('#ModalEdit #color').val(event.color);
					$('#ModalEdit').modal('show');
				});
			},
			eventDrop: function(event, delta, revertFunc) { // si changement de position

				edit(event);

			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

				edit(event);

			},
			events: [
			<?php foreach($events as $event):
        //Récupératioinet convertion des dates
        $date_start = new MongoDB\BSON\UTCDateTime((String)$event->start);
        $date_end = new MongoDB\BSON\UTCDateTime((String)$event->end);

				$start = explode(" ", $date_start->toDateTime()->format('Y-m-d\ H:i:s'));
				$end = explode(" ", $date_end->toDateTime()->format('Y-m-d\ H:i:s'));
				if($start[1] == '00:00:00'){
					$start = $start[0];
				}else{
					$start = $date_start->toDateTime()->format('Y-m-d\ H:i:s');
				}
				if($end[1] == '00:00:00'){
					$end = $end[0];
				}else{
					$end = $date_end->toDateTime()->format('Y-m-d\ H:i:s');
				}
			?>
				{
					id: '<?php echo $event->_id; ?>',
					title: '<?php echo $event->title; ?>',
          name: '<?php echo $event->name; ?>',
					start: '<?php echo $date_start->toDateTime()->format('Y-m-d\ H:i:s'); ?>',
					end: '<?php echo $date_end->toDateTime()->format('Y-m-d\ H:i:s'); ?>',
					color: '<?php echo $event->color; ?>',
				},
			<?php endforeach; ?>
			]
		});

		function edit(event){
			start = event.start.format('YYYY-MM-DD HH:mm:ss');
			if(event.end){
				end = event.end.format('YYYY-MM-DD HH:mm:ss');
			}else{
				end = start;
			}

			id =  event.id;

			Event = [];
			Event[0] = id;
			Event[1] = start;
			Event[2] = end;

        $.ajax({
         url: 'editEventDate.php',
         type: "POST",
         data: {Event:Event},
         success: function(rep) {
            if(rep == 'OK'){
              //alert('Enregistré');
            }else{
              alert('Ne peut être enregistré, essayer à nouveau..');
            }
          }
        });
		}

	});
//Datetimpepicker
  $("#start").datetimepicker({
    startDate:'+1971/05/01'
  });
  $("#end").datetimepicker({
    startDate:'+1971/05/01'
  });
</script>

</body>

</html>
