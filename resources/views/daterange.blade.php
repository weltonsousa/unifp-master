<html>
  <head>
      <meta name="viewport" content = "width=device-width, initial-scale=1" >
    <title>Matrículas </title>
          <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" > </script>
          <link rel = "stylesheet" href ="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
          <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" > </script>
          <script src = "https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js" > </script>
          <link rel = "stylesheet" href ="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css"/>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"> </script>
          <link rel = "stylesheet" href ="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"> </script>
  </head>
<body>
      <div class="container">
      <br/>
        <h3 align="center"> Matrículas</h3>
          <br />
          <br />
        <div class="row input-daterange">
      <div class="col-md-4" >
          <input type="text" name = "from_date" id = "from_date" class="form-control" placeholder = "Data inicio" readonly/>
        </div>
      <div class="col-md-4" >
           <input type="text" name = "to_date" id = "to_date" class="form-control" placeholder = "Data fim" readonly/>
         </div>
      <div class="col-md-4" >
              <button type="button" name = "filter" id = "filter" class="btn btn-primary" > Filtro </button>
              <button type = "button" name = "refresh" id = "refresh" class="btn btn-default" > Limpar </button>
        </div>
          </div>
          <br />
          <div class="table-responsive" >
            <table class="table table-bordered table-striped" id = "order_table">
              <thead>
              <tr>
              <th>Order ID </th>
                <th>Nome </th>
                  <th> Email </th>
                  <th> Telefone </th>
                  <th> Data </th>
                  </tr>
                  </thead>
                  </table>
                  </div>
                  </div>
   </body>
</html>

<script>
    $(document).ready(function () {
      $('.input-daterange').datepicker({
        todayBtn: 'linked',
        format: 'yyyy-mm-dd',
        autoclose: true
      });

  load_data();

  function load_data(from_date = '', to_date = '') {
    $('#order_table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route("daterange.index") }}',
        data: { from_date: from_date, to_date: to_date }
      },
      columns: [
        {
          data: 'pag_id',
          name: 'pag_id'
        },
        {
          data: 'pag_nome',
          name: 'pag_nome'
        },
        {
          data: 'pag_email',
          name: 'pag_email'
        },
        {
          data: 'pag_telefone',
          name: 'pag_telefone'
        },
        {
          data: 'pag_data',
          name: 'pag_data'
        }
      ]
    });
  }

  $('#filter').click(function () {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    if (from_date != '' && to_date != '') {
      $('#order_table').DataTable().destroy();
      load_data(from_date, to_date);
    }
    else {
      alert('Data de inicio é requerido');
    }
  });

  $('#refresh').click(function () {
    $('#from_date').val('');
    $('#to_date').val('');
    $('#order_table').DataTable().destroy();
    load_data();
  });

});
</script>
