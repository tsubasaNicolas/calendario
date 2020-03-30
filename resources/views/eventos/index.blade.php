@extends('layouts.app')
@section('scripts')
 
<link rel="stylesheet" 
href="{{asset('fullcalendar/core/main.css')}}">
<link rel="stylesheet" 
href="{{asset('fullcalendar/daygrid/main.css')}}">
<link rel="stylesheet" 
href="{{ asset('fullcalendar/list/main.css')}}">
<link rel="stylesheet" 
href="{{ asset('fullcalendar/timegrid/main.css')}}">

<script src="{{ asset('fullcalendar/core/main.js')}}"></script>
<script src="{{ asset('fullcalendar/daygrid/main.js')}}"></script>
<script src="{{ asset('fullcalendar/list/main.js')}}"></script>
<script src="{{ asset('fullcalendar/timegrid/main.js')}}"></script>
<script src="{{ asset('fullcalendar/interaction/main.js')}}"></script>

<script>

    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid', 'timeGrid', 'list', 'interaction'],
      //  defaultView:'timeGridWeek'
        //timeGridDay

        header:{
            left:'prev,next today Miboton',
            center:'title',
            right:'dayGridMonth, timeGridWeek, timeGridDay'
        },
        customButtons:{
            Miboton:{
                text:"Botón",
                click:function(){
                  //  alert("Hola Mundo");
                    $('#exampleModal').modal('toggle');
                  }
            }
        },

        dateClick:function(info){

            $('#txtFecha').val(info.dateStr);

            $('#exampleModal').modal('toggle');
         //   console.log(info);
           // calendar.addEvent({ title:"evento ºx", date:info.dateStr});
        },
        eventClick:function(info){
            console.log(info);
            console.log(info.event.title);

            console.log(info.event.start);

            console.log(info.event.end);
                console.log(info.event.textColor);
           console.log(info.event.backgroundColor);

           console.log(info.event.extendedProps.descripcion);



           $('#txtId').val(info.event.id);
           $('#txtTitulo').val(info.event.title);

           

           mes = (info.event.start.getMonth()+1);
           dia = (info.event.start.getDate());
           año = (info.event.start.getFullYear());

           mes =(mes<10)?"0"+mes:mes;
           dia=(dia<10)?"0"+dia:dia;

           minutos=(info.event.start.getMinutes());
           minutos= (minutos<10)?"0"+minutos:minutos;
           hora = (info.event.start.getHours()+":"+minutos);
           
      
           

           $('#txtFecha').val(año+"-"+mes+"-"+dia);
           
           $('#txtHora').val(hora);   
           $('#txtColor').val(info.event.backgroundColor);   
           $('#txtDescripcion').val(info.event.extendedProps.descripcion);

           $('#exampleModal').modal('toggle');

        },

      /*  events:[
            {
                title:'evento 1',
                start:'2020-03-25 12:30:00',
                descripcion:"descripción 1"
            },{
                title:'evento 2',
                start:'2020-03-26 12:30:00',
                end:'2020-03-26 20:30:00',
                color:"#FFCCAA",
                textColor:'#000000',
                descripcion:"descripcion 2"
            }
        ]*/
            events:"{{ url('/eventos/show') }}"
      });
      

      calendar.setOption('locale','Es');

      calendar.render();

      $('#btnAgregar').click(function(){

          ObjEvento = recolectarDatosGui("POST"); 
          
          EnviarInformacion('',ObjEvento);
      
      });
      $('#btnEliminar').click(function(){

      ObjEvento = recolectarDatosGui("DELETE"); 

      EnviarInformacion('/'+$('#txtId').val(),ObjEvento);
  });


  $('#btnModificar').click(function(){

  ObjEvento = recolectarDatosGui("PATCH"); 

  EnviarInformacion('/'+$('#txtId').val(),ObjEvento);
  });



      function recolectarDatosGui(method){
          nuevoEvento={

              id:$('#txtId').val(),
              title:$("#txtTitulo").val(),
              descripcion:$('#txtDescripcion').val(),
              color:$('#txtColor').val(),
              textColor:'#FFFFFF',
              start:$('#txtFecha').val()+" "+$('#txtHora').val(),
              end:$('#txtFecha').val()+" "+$('#txtHora').val(),
              '_token':$("meta[name='csrf-token']").attr("content"),
              '_method':method
          }
            return (nuevoEvento);
      }
      function EnviarInformacion(accion, objEvento){
          $.ajax(
              {
                  type:"POST",
                  url:"{{ url('/eventos')}}"+accion,
                  data:objEvento,
                  success:function(msg){console.log(msg);
                   
                    $('#exampleModal').modal('toggle');
                    calendar.refetchEvents();
                  
                  },
                  error:function(){ alert("hay un error");}
              })
      }

    });

  </script>


@endsection

@section('content')
<div class="row">
    <div class="col"></div>
    <div class="col-8"><div id="calendar">Calendario ...</div></div>
    <div class="col"></div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Datos del Evento</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        
        </div>
        <div class="modal-body">
            Id:
          <input type="text" name="txtId" id="txtId">
          <br>
          Fecha:
          <input type="text" name="txtFecha" id="txtFecha">
          <br>
          
          <div class="form-row">

          <div class="form-group col-md-8">
          <label>Titulo:</label>
          <input type="text" class="form-control" name="txtTitulo" id="txtTitulo">
        </div>
        
        <div class="form-group col-md-4">
         <label for=""> Hora:</label>
          <input type="text" class="form-control" name="txtHora" id="txtHora">
        </div>

        <div class="form-group col-md-12">
          <label>Descripcion:</label>     
          <textarea name="txtDescripcion" class="form-control" id="txtDescripcion" cols="30" rows="4"></textarea>
        </div>

        <div class="form-group col-md-12">
          <label for="">Color:</label>   
          <input type="color" class="form-control" name="txtColor" id="txtColor">
        </div>

      </div>
        
      </div>

      <div class="modal-footer">

              <div id="btnAgregar" class="btn btn-success">Agregar</div>
            <div id="btnModificar" class="btn btn-warning">Modificar</div>
            <div id="btnEliminar" class="btn btn-danger">Eliminar</div>
            <div id="btnCancelar" class="btn btn-default">Cancelar</div>

        </div>
      </div>
    </div>
  </div>




@endsection