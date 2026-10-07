function loadOptions(url, data) {
    return fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams(data)
    }).then(function (response) {
        if (!response.ok) throw new Error('Adres seçenekleri yüklenemedi.');
        return response.json();
    });
}

var cartBadge = document.querySelector('.sepetAdet');
if (cartBadge) {
    var cartCount = Number(cartBadge.textContent);
    if (cartCount >= 10) cartBadge.textContent = '9+';
    if (cartCount > 0) cartBadge.classList.add('sepetteUrun');
}

var citySelect = document.getElementById('sehir');
var districtSelect = document.getElementById('ilce');
var neighborhoodSelect = document.getElementById('mahalle');
var districtWrapper = document.querySelector('.ilce');
var neighborhoodWrapper = document.querySelector('.mahalle');

function resetSelect(select, label) {
    if (!select) return;
    select.innerHTML = '<option value="">' + label + '</option>';
}

if (citySelect && districtSelect && neighborhoodSelect) {
    citySelect.addEventListener('change', function () {
        resetSelect(districtSelect, 'İlçe seçiniz');
        resetSelect(neighborhoodSelect, 'Mahalle seçiniz');
        if (districtWrapper) districtWrapper.style.display = this.value ? 'block' : 'none';
        if (neighborhoodWrapper) neighborhoodWrapper.style.display = 'none';
        if (!this.value) return;
        loadOptions('parts/adres_getir.php', {il_id: this.value})
            .then(function (districts) {
                districts.forEach(function (district) {
                    districtSelect.insertAdjacentHTML('beforeend', '<option value="' + district.id + '">' + district.ilce_adi + '</option>');
                });
            })
            .catch(function () { resetSelect(districtSelect, 'İlçeler yüklenemedi'); });
    });

    districtSelect.addEventListener('change', function () {
        resetSelect(neighborhoodSelect, 'Mahalle seçiniz');
        if (neighborhoodWrapper) neighborhoodWrapper.style.display = this.value ? 'block' : 'none';
        if (!this.value) return;
        loadOptions('parts/adres_getir.php', {ilce_id: this.value})
            .then(function (neighborhoods) {
                neighborhoods.forEach(function (neighborhood) {
                    neighborhoodSelect.insertAdjacentHTML('beforeend', '<option value="' + neighborhood.id + '">' + neighborhood.mahalle_adi + '</option>');
                });
            })
            .catch(function () { resetSelect(neighborhoodSelect, 'Mahalleler yüklenemedi'); });
    });
}
