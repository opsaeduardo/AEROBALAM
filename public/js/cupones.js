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

      $.ajax({
        url: base_url + url,
        type: method,
        data: $('#frmCupon').serialize(),
        success: () => {
          $('#cuponModal').modal('hide');
          tabla.ajax.reload(null, false);
          toastr.success('Cupón guardado correctamente');
        },
        error: () => toastr.error('Error al guardar el cupón')
      });
    });

    $('#tblCupones').on('click', '.btn-deact', function () {
      const id = $(this).data('id');
      Swal.fire({
        title: '¿Desactivar cupón?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
      }).then(r => {
        if (r.isConfirmed) {
          $.ajax({
            url: base_url + 'cupones/' + id,
            type: 'PATCH',
            success: () => {
              tabla.ajax.reload(null, false);
              toastr.info('Cupón desactivado');
            },
            error: () => toastr.error('Error al desactivar el cupón')
          });
        }
      });
    });

    $('#tblCupones').on('click', '.btn-activate', function () {
      const id = $(this).data('id');
      $.ajax({
        url: base_url + 'cupones/activate/' + id,
        type: 'PATCH',
        success: () => {
          tabla.ajax.reload(null, false);
          toastr.success('Cupón reactivado');
        },
        error: () => toastr.error('Error al reactivar el cupón')
      });
    });
  });
