// public/js/perfil.js
$(function () {
  const ruta = $('#ruta').val();
  let original = {};

  $('#perfilModal').on('show.bs.modal', () => {
    $.getJSON(`${ruta}perfil/info`, r => {
      if (r.status === 'success') {
        const d = r.data;
        original = d;
        $('#frmPerfil [name=nombre]').val(d.nombre).next('.original-value').text(`Actual: ${d.nombre}`);
        $('#frmPerfil [name=usuario]').val(d.usuario).next('.original-value').text(`Actual: ${d.usuario}`);
        $('#frmPerfil [name=correo]').val(d.correo).next('.original-value').text(`Actual: ${d.correo}`);
        $('#frmPerfil [name=telefono]').val(d.telefono).next('.original-value').text(`Actual: ${d.telefono ?? '-'}`);
        $('#frmPerfil [name=rol]').val(d.rol).next('.original-value').text(`Actual: ${d.rol}`);
      }
    });
  });

  $('#btnGuardarPerfil').click(() => {
    const current = {
      nombre: $('#frmPerfil [name=nombre]').val(),
      correo: $('#frmPerfil [name=correo]').val(),
      telefono: $('#frmPerfil [name=telefono]').val()
    };

    let diffHtml = '';
    Object.keys(current).forEach(k => {
      if (current[k] !== original[k]) {
        diffHtml += `<tr><td>${k}</td><td>${original[k] || '-'}</td><td>${current[k] || '-'}</td></tr>`;
      }
    });

    if (!diffHtml) {
      Swal.fire('Sin cambios', 'No modificaste ningún dato.', 'info');
      return;
    }

    Swal.fire({
      title: 'Confirmar cambios',
      html: `<table class="table table-sm"><thead><tr><th>Campo</th><th>Antes</th><th>Nuevo</th></tr></thead><tbody>${diffHtml}</tbody></table>`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Guardar',
      cancelButtonText: 'Cancelar',
      reverseButtons: true
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url: `${ruta}perfil`,
          method: 'PUT',
          contentType: 'application/json',
          data: JSON.stringify(current),
          beforeSend: () => {
            Swal.fire({
              title: 'Guardando...',
              allowOutsideClick: false,
              didOpen: () => Swal.showLoading()
            });
          },
          success: r => {
            if (r.status === 'success') {
              Swal.fire('¡Actualizado!', 'Perfil actualizado correctamente.', 'success');
              $('#perfilModal').modal('hide');
            } else {
              Swal.fire('Error', 'Algo salió mal al actualizar tu perfil.', 'error');
            }
          },
          error: () => {
            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
          }
        });
      }
    });
  });
});
