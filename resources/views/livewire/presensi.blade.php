<div>
    <div class="container mx-auto max-w-sm">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Informasi Pegawai</h2>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p><strong>Nama Pegawai: </strong> {{$schedule->user->name }}</p>
                        <p><strong>Kantor: </strong> {{$schedule->office->name}}</p>
                        <p><strong>Shift: </strong>{{$schedule->shift->name  }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="bg-gray-200  p-4 rounded-19">
                        <h4 class="font-bold">jam masuk</h4>
                        <p class="text-lg font-bold">08:00</p>
                    </div>
                    <div class="bg-gray-200 p-4 rounded-19"> 
                       <h4 class="font-bold">jam keluar</h4> 
                       <p class="text-lg font-bold">17:00</p>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-bold mb-2">Presensi</h2>
                     <div id="map" class="mb-4 border border-gray-300 rounded" wire:ignore></div>
                        
                     </div>
                     <br>
                    <button type="button" onclick="tagLocation()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-900 hover:px-7 hover:py-4 transition-all duration-700 cursor-pointer">Tag Location</button>
                    @if ($insideRadius)    
                        <button type="button" onclick="tagLocation()" class="px-4 py-2 bg-emerald-500 text-white rounded cursor-pointer hover:bg-emerald-900 transition-all">Submit Presensi</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    
    let marker;
    let map;
    let lang;
    let lat;
    let component;
    let office = [{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}];
    let radius = [{{ $schedule->office->radius }}];
    
    document.addEventListener('livewire:initialized', function () {
        component = @this; 
        map = L.map('map').setView([{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}], 17);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);
        var circle = L.circle(office, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(map);
    });
    

    function tagLocation() {
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position){
                lat = position.coords.latitude;
                lng = position.coords.longitude;

                if(marker){
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], 17);  
                
                if(isWithinRadius(lat, lng, office, radius)){
                   component.set('insideRadius', true);
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