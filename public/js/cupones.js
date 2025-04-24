$(function () {
  const base_url = $('#ruta').val();

  const tabla = $('#tblCupones').DataTable({
    ajax: {
      url: base_url + 'cupones/list',
      type: 'GET',
      dataSrc: 'data',
      error: (xhr) => {
        Swal.fire({
          icon: 'error',
          title: 'Error al cargar',
          text: 'No se pudieron cargar los cupones.',
        });
      }
    },
    columns: [
      { data: 'Nombre' },
      {
        data: 'Descuento',
        render: d => `<span class="fw-semibold text-primary">${d}%</span>`
      },
      {
        data: 'Limite',
        render: l => `<span class="badge text-bg-secondary">${l}</span>`
      },
      {
        data: 'Status',
        render: s =>
          s == 1
            ? '<span class="badge rounded-pill bg-success px-3 py-2">Activo</span>'
            : '<span class="badge rounded-pill bg-danger px-3 py-2">Inactivo</span>'
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: (_, __, row) => {
          if (row.Status == 1) {
            return `
              <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-sm btn-outline-primary btn-edit" title="Editar" data-id="${row.Id}">
                  <i class="fa-solid fa-pen"></i>
                </button>
                <button class="btn btn-sm btn-outline-warning btn-deact" title="Desactivar" data-id="${row.Id}">
                  <i class="fa-solid fa-ban"></i>
                </button>
              </div>`;
          } else {
            return `
              <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-sm btn-outline-success btn-activate" title="Reactivar" data-id="${row.Id}">
                  <i class="fa-solid fa-rotate-left"></i>
                </button>
              </div>`;
          }
        }
      }
    ],
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    responsive: {
      details: {
        type: 'column',
        target: 'tr'
      }
    },
    columnDefs: [
      { className: 'text-center align-middle', targets: '_all' }
    ],
    scrollX: false,
    autoWidth: false,
    pageLength: 10,
    lengthMenu: [10, 50, 100],
    dom: '<"row mb-3"<"col-sm-6"l><"col-sm-6 text-end"f>>' +
         'rt' +
         '<"row mt-3"<"col-sm-6"i><"col-sm-6 text-end"Bp>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="fa-solid fa-file-excel me-1"></i>Excel',
        className: 'btn btn-outline-success btn-sm'
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="fa-solid fa-file-pdf me-1"></i>PDF',
        className: 'btn btn-outline-danger btn-sm'
      }
    ]
  });

  // Nuevo cupón
  $('#btnNuevo').on('click', () => {
    $('#frmCupon')[0].reset();
    $('#Id').val('');
    $('#tituloModal').text('Nuevo Cupón');
    $('#cuponModal').modal('show');
  });

  // Editar cupón
  $('#tblCupones').on('click', '.btn-edit', function () {
    const id = $(this).data('id');
    $.get(base_url + 'cupones/list', d => {
      const cup = d.data.find(c => c.Id == id);
      Object.entries(cup).forEach(([k, v]) => $('#frmCupon [name="' + k + '"]').val(v));
      $('#tituloModal').text('Editar Cupón');
      $('#cuponModal').modal('show');
    });
  });

  // Submit form (crear/actualizar)
  $('#frmCupon').on('submit', e => {
    e.preventDefault();
    const id = $('#Id').val();
    const url = id ? 'cupones/' + id : 'cupones/store';
    const method = id ? 'PUT' : 'POST';
    const $btn = $('#frmCupon button[type="submit"]');

    $.ajax({
      url: base_url + url,
      type: method,
      data: $('#frmCupon').serialize(),
      beforeSend: function () {
        Swal.fire({
          title: id ? 'Actualizando cupón...' : 'Guardando cupón...',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });
        $btn.prop('disabled', true);
      },
      success: function () {
        Swal.close();
        $btn.prop('disabled', false);
        $('#cuponModal').modal('hide');
        tabla.ajax.reload(null, false);
        Swal.fire('¡Listo!', 'Cupón guardado correctamente.', 'success');
      },
      error: function (xhr) {
        Swal.close();
        $btn.prop('disabled', false);

        if (xhr.status === 422 && xhr.responseJSON.errors) {
          const errors = xhr.responseJSON.errors;
          const list = Object.values(errors).flat().map(msg => `<li>${msg}</li>`).join('');
          Swal.fire({
            icon: 'warning',
            title: 'Error de validación',
            html: `<ul style="text-align:left">${list}</ul>`
          });
        } else if (xhr.status >= 500 || xhr.status === 0) {
          Swal.fire('¡Uy!', 'Error de servidor o sin conexión.', 'error');
        } else {
          Swal.fire('¡Uy!', 'No se pudo guardar el cupón.', 'error');
        }
      }
    });
  });

  // Desactivar cupón
  $('#tblCupones').on('click', '.btn-deact', function () {
    const $btn = $(this);
    const id = $btn.data('id');

    Swal.fire({
      title: '¿Desactivar cupón?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, desactivar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Desactivando...',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });
        $btn.prop('disabled', true);

        $.ajax({
          url: base_url + 'cupones/' + id,
          type: 'PATCH',
          success: function () {
            Swal.close();
            tabla.ajax.reload(null, false);
            Swal.fire('Desactivado', 'Cupón desactivado correctamente.', 'info');
          },
          error: function (xhr) {
            Swal.close();
            if (xhr.status >= 500 || xhr.status === 0) {
              Swal.fire('¡Uy!', 'Error al desactivar el cupón.', 'error');
            } else {
              Swal.fire('¡Uy!', 'No se pudo desactivar el cupón.', 'error');
            }
          },
          complete: function () {
            $btn.prop('disabled', false);
          }
        });
      }
    });
  });

  // Reactivar cupón
  $('#tblCupones').on('click', '.btn-activate', function () {
    const $btn = $(this);
    const id = $btn.data('id');

    Swal.fire({
      title: 'Reactivar cupón?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, reactivar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Reactivando...',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });
        $btn.prop('disabled', true);

        $.ajax({
          url: base_url + 'cupones/activate/' + id,
          type: 'PATCH',
          success: function () {
            Swal.close();
            tabla.ajax.reload(null, false);
            Swal.fire('Reactivado', 'Cupón reactivado correctamente.', 'success');
          },
          error: function (xhr) {
            Swal.close();
            if (xhr.status >= 500 || xhr.status === 0) {
              Swal.fire('¡Uy!', 'Error al reactivar el cupón.', 'error');
            } else {
              Swal.fire('¡Uy!', 'No se pudo reactivar el cupón.', 'error');
            }
          },
          complete: function () {
            $btn.prop('disabled', false);
          }
        });
      }
    });
  });
});
