<div class="modal fade" id="cuponModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="frmCupon" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Nuevo Cupón</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="Id" id="Id">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="Nombre" class="form-control" required maxlength="20">
        </div>
        <div class="mb-3">
          <label class="form-label">% Descuento</label>
          <input type="number" name="Descuento" class="form-control" min="0" max="100" step="0.01" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Límite de usos</label>
          <input type="number" name="Limite" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Estado</label>
          <select name="Status" class="form-select">
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Guardar</button>
      </div>
    </form>
  </div>
</div>
