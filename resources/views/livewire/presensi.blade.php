<div>
    <div class="container mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Informasi Pegawai</h2>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p><strong>Nama Pegawai: </strong> {{$schedule->user->name }}</p>
                        <p><strong>Kantor: </strong> {{$schedule->office->name}}</p>
                        <p><strong>Shift: </strong>{{$schedule->shift->name  }}</p>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-bold mb-2">Presensi</h2>
                     <div id="map"></div>
                     <br>
                    <button type="button" onclick="tagLocation()" class="px-4 py-2 bg-blue-500 text-white rounded">Tag Location</button>
                    <button type="button" onclick="tagLocation()" class="px-4 py-2 bg-emerald-500 text-white rounded">Submit Presensi</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var map = L.map('map').setView([{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}], 17);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    let marker;
    let office = [{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}];
    let radius = [{{ $schedule->office->radius }}];

    var circle = L.circle(office, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: radius
    }).addTo(map);

    function tagLocation() {
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position){
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                if(marker){
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], 17);  
                
                if(isWithinRadius(lat, lng, office, radius)){
                    alert('Presensi Berhasil anda berada di radius kantor !');
                }else{
                    alert('Presensi Gagal anda tidak berada di radius kantor !');
                }
            });
        }else{
            alert('lokasi tidak berfungsi');
        }
    }

    function isWithinRadius(lat, lng, center, radius) {
        let distance = map.distance([lat, lng], center);
        return distance <= radius;
    }
    </script>