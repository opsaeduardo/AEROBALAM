$(function () {
  const base_url = $('#ruta').val();
  const notyf = new Notyf({
    duration: 3500,
    ripple: false,
    dismissible: true,
    position: { x: 'right', y: 'top' },
    types: [
      {
        type: 'success',
        background: 'linear-gradient(135deg, #A8E063 0%, #56AB2F 100%)',
        icon: { className: 'fa-solid fa-check-circle', tagName: 'i', text: '' }
      },
      {
        type: 'error',
        background: 'linear-gradient(135deg, #FF9966 0%, #FF5E62 100%)',
        icon: { className: 'fa-solid fa-times-circle', tagName: 'i', text: '' }
      },
      {
        type: 'info',
        background: 'linear-gradient(135deg, #89F7FE 0%, #66A6FF 100%)',
        icon: { className: 'fa-solid fa-info-circle', tagName: 'i', text: '' }
      },
      {
        type: 'warning',
        background: 'linear-gradient(135deg, #FBD786 0%, #f7797d 100%)',
        icon: { className: 'fa-solid fa-exclamation-circle', tagName: 'i', text: '' }
      }
    ]
  });

  $.ajaxSetup({
    error(xhr, status, error) {
      notyf.error('Error en la petición: ' + (xhr.responseJSON?.message || error));
    }
  });

  const tabla = $('#tblCupones').DataTable({
    ajax: { url: base_url + 'cupones/list', type: 'GET', dataSrc: 'data', error(xhr) {
      notyf.error('No se pudieron cargar los cupones');
    }},
    columns: [
      { data: 'Nombre', responsivePriority: 1 },
      { data: 'Descuento', render: d => `<span class="fw-semibold text-primary">${d}%</span>`, responsivePriority: 3 },
      { data: 'Limite', render: l => `<span class="badge text-bg-secondary">${l}</span>`, responsivePriority: 4 },
      { data: 'Status', render: s =>
        s == 1
          ? '<span class="badge rounded-pill bg-success px-3 py-2">Activo</span>'
          : '<span class="badge rounded-pill bg-danger px-3 py-2">Inactivo</span>', responsivePriority: 2
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: (_, __, row) => row.Status == 1
          ? `<div class="d-flex gap-2 justify-content-center">
              <button class="btn btn-sm btn-outline-primary btn-edit" title="Editar" data-id="${row.Id}">
                <i class="fa-solid fa-pen"></i>
              </button>
              <button class="btn btn-sm btn-outline-warning btn-deact" title="Desactivar" data-id="${row.Id}">
                <i class="fa-solid fa-ban"></i>
              </button>
            </div>`
          : `<div class="d-flex gap-2 justify-content-center">
              <button class="btn btn-sm btn-outline-success btn-activate" title="Reactivar" data-id="${row.Id}">
                <i class="fa-solid fa-rotate-left"></i>
              </button>
            </div>`,
        responsivePriority: 5
      }
    ],
    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
    responsive: { details: { type: 'column', target: 'tr' } },
    columnDefs: [{ className: 'text-center align-middle', targets: '_all' }],
    scrollX: false,
    autoWidth: false,
    pageLength: 10,
    lengthMenu: [10, 50, 100],
    dom:
      '<"row mb-3"<"col-12 col-md-6"l><"col-12 col-md-6 text-md-end text-start"f>>' +
      'rt' +
      '<"row mt-3"<"col-12 col-md-6"i><"col-12 col-md-6 text-md-end text-start"Bp>>',
    buttons: [
      { extend: 'excelHtml5', text: '<i class="fa-solid fa-file-excel me-1"></i>Excel', className: 'btn btn-outline-success btn-sm' },
      { extend: 'pdfHtml5',   text: '<i class="fa-solid fa-file-pdf me-1"></i>PDF',   className: 'btn btn-outline-danger btn-sm' }
    ]
  });

  $('#btnNuevo').on('click', () => {
    $('#frmCupon')[0].reset();
    $('#Id').val('');
    $('#tituloModal').text('Nuevo Cupón');
    $('#cuponModal').modal('show');
  });

  $('#tblCupones').on('click', '.btn-edit', function () {
    const id = $(this).data('id');
    $.get(base_url + 'cupones/list', d => {
      const cup = d.data.find(c => c.Id == id);
      Object.entries(cup).forEach(([k, v]) => $('#frmCupon [name="' + k + '"]').val(v));
      $('#tituloModal').text('Editar Cupón');
      $('#cuponModal').modal('show');
    });
  });

  $('#frmCupon').on('submit', e => {
    e.preventDefault();
    const id = $('#Id').val();
    const url = id ? 'cupones/' + id : 'cupones/store';
    const method = id ? 'PUT' : 'POST';
    $.ajax({ url: base_url + url, type: method, data: $('#frmCupon').serialize(), success() {
      $('#cuponModal').modal('hide');
      tabla.ajax.reload(null, false);
      notyf.success('Cupón guardado correctamente');
    }});
  });

  $('#tblCupones').on('click', '.btn-deact', function () {
    const id = $(this).data('id');
    Swal.fire({
      title: '<strong>¿Desactivar cupón?</strong>',
      html: 'Esta acción desactivará el cupón de forma permanente.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '<i class="fa-solid fa-check me-1"></i> Desactivar',
      cancelButtonText: '<i class="fa-solid fa-times me-1"></i> Cancelar',
      customClass: {
        popup: 'border-0 shadow p-4 rounded-4',
        title: 'fw-bold text-dark',
        confirmButton: 'btn btn-primary me-2',
        cancelButton: 'btn btn-outline-secondary'
      },
      buttonsStyling: false,
      background: '#ffffff'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({ url: base_url + 'cupones/' + id, type: 'PATCH', success() {
          tabla.ajax.reload(null, false);
          notyf.success('Operación exitosa: cupón desactivado');
        }});
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        notyf.open({ type: 'warning', message: 'Operación cancelada' });
      }
    });
  });

  $('#tblCupones').on('click', '.btn-activate', function () {
    const id = $(this).data('id');
    $.ajax({ url: base_url + 'cupones/activate/' + id, type: 'PATCH', success() {
      tabla.ajax.reload(null, false);
      notyf.success('Cupón reactivado');
    }});
  });
});
