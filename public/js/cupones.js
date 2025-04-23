$(function () {

  const base_url = $('#ruta').val();

    const tabla = $('#tblCupones').DataTable({
        ajax: {
            url: base_url + 'cupones/list',
            type: 'GET',
            dataSrc: 'data',         
            error: (xhr) => {
                console.error('❌ AJAX Cupones:', xhr.status, xhr.responseText);
                toastr.error('No se pudieron cargar los cupones');
            }
        },
        columns: [
            { data: 'Nombre' },
            { data: 'Descuento', render: d => d + '%' },
            { data: 'Limite'    },
            { data: 'Status',   render: s => s == 1
                                   ? '<span class="badge bg-success">Activo</span>'
                                   : '<span class="badge bg-danger">Inactivo</span>' },
            { data: null, orderable: false, searchable: false,
              render: (_, __, row) => `
                  <button class="btn btn-sm btn-info btn-edit" data-id="${row.Id}">
                     <i class="fa-solid fa-pen"></i>
                  </button>
                  <button class="btn btn-sm btn-warning btn-deact" data-id="${row.Id}">
                     <i class="fa-solid fa-ban"></i>
                  </button>`
            }
        ],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' }
    });

  /* ---------- Alta ---------- */
  $('#btnNuevo').on('click', () => {
      $('#frmCupon')[0].reset();
      $('#Id').val('');
      $('#tituloModal').text('Nuevo Cupón');
      $('#cuponModal').modal('show');
  });

  /* ---------- Editar ---------- */
  $('#tblCupones').on('click', '.btn-edit', function () {
      const id = $(this).data('id');
      $.get(base_url + 'cupones/list', d => {
          const cup = d.data.find(c => c.Id == id);
          Object.entries(cup).forEach(([k, v]) => $('#frmCupon [name="'+k+'"]').val(v));
          $('#tituloModal').text('Editar Cupón');
          $('#cuponModal').modal('show');
      });
  });

  /* ---------- Guardar ---------- */
  $('#frmCupon').on('submit', e => {
      e.preventDefault();
      const id = $('#Id').val();
      const url = id ? 'cupones/'+id : 'cupones/store';
      const method = id ? 'PUT' : 'POST';

      $.ajax({
          url:  base_url + url,
          type: method,
          data: $('#frmCupon').serialize(),
          success: () => {
              $('#cuponModal').modal('hide');
              tabla.ajax.reload(null, false);
              toastr.success('Cupón guardado');
          },
          error: () => toastr.error('Error al guardar')
      });
  });

  /* ---------- Desactivar ---------- */
  $('#tblCupones').on('click', '.btn-deact', function () {
      const id = $(this).data('id');
      Swal.fire({
          title: '¿Desactivar cupón?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, desactivar'
      }).then(r => {
          if (r.isConfirmed) {
              $.ajax({
                  url: base_url + 'cupones/'+id,
                  type: 'PATCH',
                  success: () => {
                      tabla.ajax.reload(null, false);
                      toastr.info('Cupón desactivado');
                  }
              });
          }
      });
  });
});
