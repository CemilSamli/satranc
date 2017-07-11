
<script src="/web/app/assets/javascript/jquery-1.10.1.min.js"></script>
<script src="/web/app/assets/javascript/setup.js"></script>
<input type="hidden" id="status" value="<?php echo $args['status']; ?>">
<p id="basarisiz" style="display: none">Kurulum esnasında bir hata oluştu :(</p>
<p id="basarili" style="display: none">Sistem başarılı bir şekilde kuruldu.</p>
<p id="kurulu" style="display: none">Eski veritabanını silip yeniden kurmak istermisiniz?</p>
<p id="kuruludegil" style="display: none">Sistem kurulu değil, kurmak istermisiniz?</p>
<p id="loading" style="display: none">Kurulum yapılıyor lütfen bekleyin.</p>
<button id="kur">Kur</button>
