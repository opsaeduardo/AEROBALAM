<!-- app/Views/dashboard/perfil/modal_profile.php -->
<div class="modal fade" id="perfilModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header text-white" style="background:linear-gradient(135deg,#007bff 0%,#00c6ff 100%)">
        <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-gear me-2"></i>Mi perfil</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="frmPerfil" class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
            <div class="form-text original-value"></div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Usuario</label>
            <input type="text" name="usuario" class="form-control" disabled>
            <div class="form-text original-value"></div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" required>
            <div class="form-text original-value"></div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control">
            <div class="form-text original-value"></div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Rol</label>
            <input type="text" name="rol" class="form-control" disabled>
            <div class="form-text original-value"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer bg-light border-0 rounded-bottom-4">
        <button class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>Cerrar</button>
        <button id="btnGuardarPerfil" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Guardar cambios</button>
      </div>
    </div>
  </div>
</div>
