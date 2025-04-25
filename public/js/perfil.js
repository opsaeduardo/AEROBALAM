// public/js/perfil.js
$(function () {
  const ruta = $('#ruta').val()
  let original = {}

  const toggleOriginal = (input) => {
    const key = input.attr('name')
    const label = input.next('.original-value')
    if (input.prop('disabled') || key === 'rol' || key === 'password') return
    if (input.val() !== original[key]) {
      label.text(`Actual: ${original[key] ?? '-'}`).removeClass('d-none')
    } else {
      label.addClass('d-none')
    }
  }

  $('#perfilModal').on('show.bs.modal', () => {
    $.getJSON(`${ruta}perfil/info`, r => {
      if (r.status !== 'success') return
      const d = r.data
      original = { ...d }
      $('#statusBadge').css('background', d.status === 1 ? '#28a745' : '#dc3545')
      $('#frmPerfil [name=id]').val(d.id)
      $('#frmPerfil [name=usuario]').val(d.usuario)
      $('#frmPerfil [name=rol]').val(d.rol)
      $('#frmPerfil [name=nombre]').val(d.nombre)
      $('#frmPerfil [name=edad]').val(d.edad)
      $('#frmPerfil [name=telefono]').val(d.telefono)
      $('#frmPerfil [name=correo]').val(d.correo)
      $('#frmPerfil input').each(function () { toggleOriginal($(this)) })
    })
  })

  $('#frmPerfil').on('input', 'input', function () { toggleOriginal($(this)) })

  $('#btnGuardarPerfil').click(() => {
    const current = {
      nombre: $('#frmPerfil [name=nombre]').val(),
      edad: $('#frmPerfil [name=edad]').val(),
      telefono: $('#frmPerfil [name=telefono]').val(),
      correo: $('#frmPerfil [name=correo]').val()
    }
    let diffHtml = ''
    Object.keys(current).forEach(k => {
      if (current[k] !== original[k]) diffHtml += `<tr><td>${k}</td><td>${original[k] || '-'}</td><td>${current[k] || '-'}</td></tr>`
    })
    if (!diffHtml) return Swal.fire('Sin cambios', 'No modificaste ningún dato.', 'info')

    Swal.fire({
      title: 'Confirmar cambios',
      html: `<table class="table table-sm"><thead><tr><th>Campo</th><th>Antes</th><th>Nuevo</th></tr></thead><tbody>${diffHtml}</tbody></table>`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Guardar',
      cancelButtonText: 'Cancelar',
      reverseButtons: true
    }).then(res => {
      if (!res.isConfirmed) return
      $.ajax({
        url: `${ruta}perfil`,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(current),
        beforeSend: () => Swal.fire({ title: 'Guardando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }),
        success: r => {
          if (r.status === 'success') {
            Swal.fire('¡Actualizado!', 'Perfil actualizado correctamente.', 'success')
            $('#perfilModal').modal('hide')
          } else {
            Swal.fire('Error', 'Algo salió mal al actualizar tu perfil.', 'error')
          }
        },
        error: () => Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error')
      })
    })
  })
})
