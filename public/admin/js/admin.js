let base_url = window.location.origin
let mapboxToken = "";
let geocodeToken = null;

function initPasienFeature(base_url,mapboxToken,geocodeToken){
    $('.select2').select2();
    var provinsi_id = $("#provinsi_id").val()

    $('#provinsi_id').on('select2:select', function(e) {
        provinsi_id = e.params.data.id;
    });
    
    $('.select2-kota').select2({
        allowClear: true,
        placeholder: "Pilih Kota",
        ajax: {
            url: function(params) {
                console.log("ajax func", params, provinsi_id);
                var url = `${base_url}/select2/kota/`+provinsi_id
                return url;
            },
           
            dataType: 'json',
            cache: true,
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
        }
    });

    var kota_id = $("#kota_id").val()
    $('#kota_id').on('select2:select', function(e) {
        kota_id = e.params.data.id;
    });

    $('.select2-kecamatan').select2({
        allowClear: true,
        placeholder: "Pilih Kecamatan",
        ajax: {
            url: function(params) {
                var url = `${base_url}/select2/kecamatan/`+kota_id
                return url;
            },
            dataType: 'json',
            cache: true,
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
        }
    });

    var kecamatan_id = $("#kecamatan_id").val()
    $('#kecamatan_id').on('select2:select', function(e) {
        kecamatan_id = e.params.data.id;
    });

    $('.select2-kelurahan').select2({
        allowClear: true,
        placeholder: "Pilih Kelurahan",
        ajax: {
            url: function(params) {
                var url = `${base_url}/select2/kelurahan/`+kecamatan_id
                return url;
            },
            dataType: 'json',
            cache: true,
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
        }
    });

    hubla()
 
    mapboxgl.accessToken = mapboxToken;
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [116.924, -0.331],
        zoom: 10
    });
    var geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl,
        marker:false,
        placeholder: 'Masukan kata kunci...',
        zoom:20
    })

    map.addControl(
        geocoder
    );

    let lokasiInput = null
    let kordinatLokasiInput = null
    let marker = null

    $(document).on('click','.pilih-lokasi',function(e){
        lokasiInput = $(this).closest('.form-group').find('.lokasi');
        kordinatLokasiInput = $(this).closest('.form-group').find('.kordinat_lokasi');
        
    });

    map.on('click', function(e) {
        if(marker == null){
            marker = new mapboxgl.Marker()
            .setLngLat(e.lngLat)
            .addTo(map);
        } else {
            marker.setLngLat(e.lngLat)
        }
        kordinatLokasiInput.val(e.lngLat.lat+","+e.lngLat.lng)
        $.ajax({
            url: "https://api.opencagedata.com/geocode/v1/json?q="+e.lngLat.lat+","+e.lngLat.lng+"&key="+geocodeToken,
            dataType: 'json',
            success:function(res){
                //console.log(res.results)
                lokasiInput.val(res.results[0].formatted)
                // $(lokasiInput).closest(".row").find(".interaksi_provinsi_id").val(res.results[0].components.state)
                // $(lokasiInput).closest(".row").find(".interaksi_kota_id").val(res.results[0].components.city)
            }
        })
    });

    $('#pilihLokasi').on('shown.bs.modal', function() {
        map.resize();
    });
               
    var max_fields = 15;
    var wrapper = $(".input_fields_wrap");
    var add_button = $(".add_field_button");
    var x = 1;
    $(add_button).click(function(e) {
        e.preventDefault();
        $(".input_fields_wrap").find("select").select2("destroy")
        if(x < max_fields) {
            x++;
            let el = $("#clone").clone()
            el.addClass("mt-4")
            el.show();
            el.removeAttr('id')
            el.find('input').val('');
            el.find('textarea').val('')
            el.find('.action').html(`<button  class="remove_field btn btn-danger active" type="button">Delete</button>`)
            $(wrapper).append(el);
        }

        $(".input_fields_wrap").find("select.select2").select2();
        
        hubla()
        
    });

    function hubla() {
        const se = $(".input_fields_wrap").find("select.ajax")
        for (let i = 0;  i < se.length; ++i) {
            const element = se[i];
            $(element).select2({
                allowClear: true,
                placeholder: "Pilih "+$(element).data('url'),
                ajax: {
                    url: function(params) {
                        var belongsID = $(element).closest('.form-group').prev().find('select').val()
                        var url = `${base_url}/select2/${$(element).data('url')}/${belongsID}`
                        return url;
                    },
                    dataType: 'json',
                    cache: true,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                }
            })
        }
    }

    $(wrapper).on("click", ".remove_field", function(e) {
        e.preventDefault();
        $(this).closest('.row').remove();
        x--;
    })
}