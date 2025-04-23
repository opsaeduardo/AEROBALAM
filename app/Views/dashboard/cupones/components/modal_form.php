<div class="modal fade" id="cuponModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form id="frmCupon" class="modal-content shadow rounded-4 border-0">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="tituloModal">Nuevo Cupón</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body px-4 py-3">
        <input type="hidden" name="Id" id="Id">

        <div class="mb-3">
          <label class="form-label fw-semibold">Nombre</label>
          <input type="text" name="Nombre" class="form-control shadow-sm" required maxlength="20" placeholder="Ej. CUPON20">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">% Descuento</label>
          <input type="number" name="Descuento" class="form-control shadow-sm" min="0" max="100" step="0.01" required placeholder="Ej. 20">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Límite de usos</label>
          <input type="number" name="Limite" class="form-control shadow-sm" min="1" required placeholder="Ej. 100">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Estado</label>
          <select name="Status" class="form-select shadow-sm">
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
          </select>
        </div>
      </div>

      <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
