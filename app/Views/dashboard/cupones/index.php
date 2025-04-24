<?= $this->extend('dashboard/dashboard') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm border-0 rounded-4 mb-4">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0 fw-bold text-primary">
        <i class="fa-solid fa-ticket me-2"></i>Gestión de Cupones
      </h3>
      <button id="btnNuevo" class="btn btn-primary btn-lg rounded-3 shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Nuevo Cupón
      </button>
    </div>

    <div class="table-responsive">
      <?= view('dashboard/cupones/components/table') ?>
    </div>
  </div>
</div>

<?= view('dashboard/cupones/components/modal_form') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="<?= base_url('public/js/cupones.js'); ?>"></script>
<?= $this->endSection() ?>
