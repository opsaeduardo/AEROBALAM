<?= $this->extend('dashboard/dashboard') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm border-0 rounded-4 mb-4">
  <div class="card-body p-4">
    <h3 class="fw-bold text-primary mb-3">
      <i class="fa-solid fa-calendar-days me-2"></i>Calendario de Vuelos
    </h3>
    <div id="calendar"></div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link  href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="<?= base_url('public/js/calendar.js'); ?>"></script>
<?= $this->endSection() ?>
