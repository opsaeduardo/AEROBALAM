<?= $this->extend('dashboard/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="fa-solid fa-ticket"></i> Cupones</h3>
    <button id="btnNuevo" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Nuevo cupón
    </button>
</div>

<?= view('dashboard/cupones/components/table') ?>
<?= view('dashboard/cupones/components/modal_form') ?>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('public/js/cupones.js'); ?>"></script>
<?= $this->endSection() ?>
