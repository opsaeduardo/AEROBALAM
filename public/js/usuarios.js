$(function () {
  const base_url = $('#ruta').val();

  const tabla = $('#tblUsuarios').DataTable({
    ajax: {
      url:  base_url + 'usuarios/list',
      type: 'GET',
      dataSrc: 'data',
      error: () => Swal.fire({ icon:'error', title:'Error', text:'No se pudieron cargar los usuarios.' })
    },
    columns: [
      { data:'Nombre' },
      { data:'Usuario' },
      { data:'Rol' },
      { data:'Status',
        render: s => s==='Activo'
          ? '<span class="badge rounded-pill bg-success px-3 py-2">Activo</span>'
          : '<span class="badge rounded-pill bg-danger  px-3 py-2">Inactivo</span>'
      },
      { data:null, orderable:false, searchable:false,
        render:(_,__,row)=> row.Status==='Activo'
          ? `<div class="d-flex gap-2 justify-content-center">
               <button class="btn btn-sm btn-outline-primary btn-edit"  data-id="${row.Id}" title="Editar"><i class="fa-solid fa-pen"></i></button>
               <button class="btn btn-sm btn-outline-warning btn-deact" data-id="${row.Id}" title="Desactivar"><i class="fa-solid fa-ban"></i></button>
             </div>`
          : `<div class="d-flex gap-2 justify-content-center">
               <button class="btn btn-sm btn-outline-success btn-activate" data-id="${row.Id}" title="Reactivar"><i class="fa-solid fa-rotate-left"></i></button>
             </div>`
      }
    ],
    language: { url:'//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
    responsive:{ details:{ type:'column', target:'tr' } },
    columnDefs:[{ className:'text-center align-middle', targets:'_all' }],
    dom:'<"row mb-3"<"col-sm-6"l><"col-sm-6 text-end"f>>rt<"row mt-3"<"col-sm-6"i><"col-sm-6 text-end"Bp>>',
    buttons:[
      { extend:'excelHtml5', text:'<i class="fa-solid fa-file-excel me-1"></i>Excel', className:'btn btn-outline-success btn-sm' },
      { extend:'pdfHtml5',   text:'<i class="fa-solid fa-file-pdf   me-1"></i>PDF',   className:'btn btn-outline-danger  btn-sm' }
    ]
  });

  $('#btnNuevo').on('click', () =>{
    $('#frmUsuario')[0].reset();
    $('#Id').val('');
    $('#tituloModal').text('Nuevo Usuario');
    $('#usuarioModal').modal('show');
  });

  $('#tblUsuarios').on('click','.btn-edit',function (){
    const id = $(this).data('id');
    $.get(base_url+'usuarios/list',d=>{
      const user = d.data.find(r=>r.Id==id);
      Object.entries(user).forEach(([k,v])=> $('#frmUsuario [name="'+k+'"]').val(v));
      $('#tituloModal').text('Editar Usuario');
      $('#usuarioModal').modal('show');
    });
  });

  $('#frmUsuario').on('submit',e=>{
    e.preventDefault();
    const id = $('#Id').val();
    const url = id ? 'usuarios/'+id : 'usuarios/store';
    const method = id ? 'PUT' : 'POST';
    const $btn = $('#frmUsuario button[type="submit"]');

    $.ajax({
      url: base_url+url,
      type: method,
      data: $('#frmUsuario').serialize(),
      beforeSend: ()=>{
        Swal.fire({
          title: id?'Actualizando usuario…':'Guardando usuario…',
          allowOutsideClick:false,
          didOpen:()=>Swal.showLoading()
        });
        $btn.prop('disabled',true);
      },
      success: ()=>{
        Swal.close(); $btn.prop('disabled',false);
        $('#usuarioModal').modal('hide');
        tabla.ajax.reload(null,false);
        Swal.fire('¡Listo!','Usuario guardado correctamente.','success');
      },
      error: xhr =>{
        Swal.close(); $btn.prop('disabled',false);
        if (xhr.status===422 && xhr.responseJSON.errors){
          const list = Object.values(xhr.responseJSON.errors).flat().map(m=>`<li>${m}</li>`).join('');
          Swal.fire({ icon:'warning', title:'Error de validación', html:`<ul style="text-align:left">${list}</ul>` });
        }else{
          Swal.fire('¡Error!','No se pudo guardar el usuario.','error');
        }
      }
    });
  });

  $('#tblUsuarios').on('click','.btn-deact',function (){
    const id = $(this).data('id');
    Swal.fire({ title:'¿Desactivar usuario?', icon:'warning', showCancelButton:true,
                confirmButtonText:'Sí, desactivar', cancelButtonText:'Cancelar' })
        .then(r=>{
          if(!r.isConfirmed) return;
          Swal.fire({ title:'Desactivando…', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });

          $.ajax({
            url: base_url+'usuarios/'+id,
            type:'PATCH',
            success:()=>{
              Swal.close(); tabla.ajax.reload(null,false);
              Swal.fire('Desactivado','Usuario desactivado correctamente.','info');
            },
            error: ()=> Swal.fire('¡Error!','Error al desactivar el usuario.','error')
          });
        });
  });

  $('#tblUsuarios').on('click','.btn-activate',function (){
    const id = $(this).data('id');
    Swal.fire({ title:'¿Reactivar usuario?', icon:'question', showCancelButton:true,
                confirmButtonText:'Sí, reactivar', cancelButtonText:'Cancelar' })
        .then(r=>{
          if(!r.isConfirmed) return;
          Swal.fire({ title:'Reactivando…', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });

          $.ajax({
            url: base_url+'usuarios/activate/'+id,
            type:'PATCH',
            success:()=>{
              Swal.close(); tabla.ajax.reload(null,false);
              Swal.fire('Reactivado','Usuario reactivado correctamente.','success');
            },
            error: ()=> Swal.fire('¡Error!','Error al reactivar el usuario.','error')
          });
        });
  });
});
