<div class="modal fade" id="destinoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form id="frmDestino" class="modal-content shadow rounded-4 border-0">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="tituloModal">Nuevo Destino</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body px-4 py-3">
        <input type="hidden" name="Id" id="Id">

        <div class="mb-3">
          <label class="form-label fw-semibold">Código</label>
          <input type="text" name="Codigo" class="form-control shadow-sm" required maxlength="10" placeholder="Ej. TGZ">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Nombre</label>
          <input type="text" name="Nombre" class="form-control shadow-sm" required maxlength="100" placeholder="Ej. Tuxtla Gutiérrez">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Estado</label>
          <select name="Status" class="form-select shadow-sm">
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
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
