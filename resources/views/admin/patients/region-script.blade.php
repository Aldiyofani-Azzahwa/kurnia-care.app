<script>
    document.addEventListener('DOMContentLoaded', function () {
        const provinceSelect = document.getElementById('province_code');
        const citySelect = document.getElementById('city_code');
        const districtSelect = document.getElementById('district_code');
        const villageSelect = document.getElementById('village_code');

        const provinceNameInput = document.getElementById('province_name');
        const cityNameInput = document.getElementById('city_name');
        const districtNameInput = document.getElementById('district_name');
        const villageNameInput = document.getElementById('village_name');

        if (!provinceSelect || !citySelect || !districtSelect || !villageSelect) {
            return;
        }

        function setOptions(selectElement, placeholder, data = []) {
            selectElement.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = placeholder;
            selectElement.appendChild(defaultOption);

            data.forEach(function (item) {
                const option = document.createElement('option');
                option.value = item.code;
                option.textContent = item.name;
                selectElement.appendChild(option);
            });
        }

        function setLoading(selectElement, text) {
            selectElement.innerHTML = '';

            const option = document.createElement('option');
            option.value = '';
            option.textContent = text;
            selectElement.appendChild(option);
        }

        function getSelectedText(selectElement) {
            if (!selectElement.value) {
                return '';
            }

            return selectElement.options[selectElement.selectedIndex].text;
        }

        async function fetchJson(url) {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data dari ' + url);
            }

            return await response.json();
        }

        async function loadProvinces() {
            try {
                setLoading(provinceSelect, 'Memuat provinsi...');

                const provinces = await fetchJson('/regions/provinces');

                setOptions(provinceSelect, 'Pilih provinsi', provinces);
            } catch (error) {
                console.error(error);
                setOptions(provinceSelect, 'Gagal memuat provinsi');
            }
        }

        provinceSelect.addEventListener('change', async function () {
            const provinceCode = this.value;

            provinceNameInput.value = getSelectedText(provinceSelect);

            cityNameInput.value = '';
            districtNameInput.value = '';
            villageNameInput.value = '';

            setOptions(citySelect, 'Pilih kabupaten/kota');
            setOptions(districtSelect, 'Pilih kecamatan');
            setOptions(villageSelect, 'Pilih desa/kelurahan');

            if (!provinceCode) {
                return;
            }

            try {
                setLoading(citySelect, 'Memuat kabupaten/kota...');

                const cities = await fetchJson(`/regions/cities/${provinceCode}`);

                setOptions(citySelect, 'Pilih kabupaten/kota', cities);
            } catch (error) {
                console.error(error);
                setOptions(citySelect, 'Gagal memuat kabupaten/kota');
            }
        });

        citySelect.addEventListener('change', async function () {
            const cityCode = this.value;

            cityNameInput.value = getSelectedText(citySelect);

            districtNameInput.value = '';
            villageNameInput.value = '';

            setOptions(districtSelect, 'Pilih kecamatan');
            setOptions(villageSelect, 'Pilih desa/kelurahan');

            if (!cityCode) {
                return;
            }

            try {
                setLoading(districtSelect, 'Memuat kecamatan...');

                const districts = await fetchJson(`/regions/districts/${cityCode}`);

                setOptions(districtSelect, 'Pilih kecamatan', districts);
            } catch (error) {
                console.error(error);
                setOptions(districtSelect, 'Gagal memuat kecamatan');
            }
        });

        districtSelect.addEventListener('change', async function () {
            const districtCode = this.value;

            districtNameInput.value = getSelectedText(districtSelect);

            villageNameInput.value = '';

            setOptions(villageSelect, 'Pilih desa/kelurahan');

            if (!districtCode) {
                return;
            }

            try {
                setLoading(villageSelect, 'Memuat desa/kelurahan...');

                const villages = await fetchJson(`/regions/villages/${districtCode}`);

                setOptions(villageSelect, 'Pilih desa/kelurahan', villages);
            } catch (error) {
                console.error(error);
                setOptions(villageSelect, 'Gagal memuat desa/kelurahan');
            }
        });

        villageSelect.addEventListener('change', function () {
            villageNameInput.value = getSelectedText(villageSelect);
        });

        loadProvinces();
    });
</script>