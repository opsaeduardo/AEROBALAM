$(function(){
  const base = $('#ruta').val();

  /* ---------- FULLCALENDAR ---------- */
  const calendar = new FullCalendar.Calendar(document.getElementById('calendar'),{
    initialView: 'dayGridMonth',
    locale: 'es',
    headerToolbar: {
      left:   'prev,next today',
      center: 'title',
      right:  'dayGridMonth,timeGridWeek,listWeek'
    },
    events: base+'calendar/events',

    eventClick: function(info){
      const id = info.event.id;
      Swal.fire({
        title: 'Cargando…',
        allowOutsideClick:false,
        didOpen: ()=>Swal.showLoading()
      });

      $.getJSON(base+'calendar/detail/'+id, data=>{
        Swal.close();

        const v  = data.vuelo;
        const ps = data.pasajeros;

        let pasHtml = ps.length
          ? `<table class="table table-sm table-bordered mb-0">
               <thead class="table-light">
                 <tr><th>Nombre</th><th>Contacto</th><th>Asiento</th><th>Reserva</th><th>Pago</th></tr>
               </thead><tbody>`+
               ps.map(p=>`<tr>
                            <td>${p.Nombre}</td>
                            <td>${p.Telefono||'-'}<br>${p.Correo||''}</td>
                            <td>${p.Asiento}</td>
                            <td>${p.EstadoReserva}</td>
                            <td>${p.EstadoPago||'N/D'}</td>
                          </tr>`).join('') +
               '</tbody></table>'
          : '<p class="mb-0">Sin pasajeros registrados.</p>';

        Swal.fire({
          title: `${v.Origen} → ${v.Destino}`,
          html: `
            <div class="text-start">
              <p><strong>Fecha:</strong> ${v.Fecha}</p>
              <p><strong>Hora:</strong> ${v.Hora}</p>
              <p><strong>Precio:</strong> $${parseFloat(v.Precio).toFixed(2)}</p>
              <p><strong>Estado:</strong> ${v.Estado}</p>
              <p><strong>Asientos disponibles:</strong> ${v.Asientos_Disponibles}</p>
              <hr>
              <h6 class="fw-bold">Pasajeros</h6>
              ${pasHtml}
            </div>`,
          width: 700
        });
      })
      .fail(()=>Swal.fire('Error','No se pudo obtener la información del vuelo','error'));
    }
  });

  calendar.render();
});
