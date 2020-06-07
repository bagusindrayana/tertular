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
        zoom: 5
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
    let koordinatLokasiInput = null
    let marker = null

    $(document).on('click','.pilih-lokasi',function(e){
        lokasiInput = $(this).closest('.form-group').find('.lokasi');
        koordinatLokasiInput = $(this).closest('.form-group').find('.koordinat_lokasi');
        
    });

    map.on('click', function(e) {
        if(marker == null){
            marker = new mapboxgl.Marker()
            .setLngLat(e.lngLat)
            .addTo(map);
        } else {
            marker.setLngLat(e.lngLat)
        }
        koordinatLokasiInput.val(e.lngLat.lat+","+e.lngLat.lng)
        $.ajax({
            url: `${base_url}/api/cek-koordinat/${e.lngLat.lat}/${e.lngLat.lng}`,
            dataType: 'json',
            success:function(res){
                //console.log(res.results)
                lokasiInput.val(res.formatted)
                
                if($(lokasiInput).closest(".row").find(".interaksi_provinsi_id").length){
                    $(lokasiInput).closest(".row").find(".interaksi_provinsi_id").val(res.provinsi.id).trigger('change');

                    var $newOption = $("<option selected='selected'></option>").val(res.kota.id).text(res.kota.nama)
                    $(lokasiInput).closest(".row").find(".interaksi_kota_id").append($newOption).trigger('change');
    
                    if(res.kecamatan.id != null){
                        $newOption = $("<option selected='selected'></option>").val(res.kecamatan.id).text(res.kecamatan.nama)
                        $(lokasiInput).closest(".row").find(".interaksi_kecamatan_id").append($newOption).trigger('change');
                    } else {
                        $(lokasiInput).closest(".row").find(".interaksi_kecamatan_id").val(null).trigger('change');
                    }
    
                    if(res.kelurahan.id != null){
                        $newOption = $("<option selected='selected'></option>").val(res.kelurahan.id).text(res.kelurahan.nama)
                        $(lokasiInput).closest(".row").find(".interaksi_kelurahan_id").append($newOption).trigger('change');
                    } else {
                        $(lokasiInput).closest(".row").find(".interaksi_kelurahan_id").val(null).trigger('change');
                    }
                }

                if($(lokasiInput).closest(".row").find('.provinsi_id').length){
                    $(lokasiInput).closest(".row").find(".provinsi_id").val(res.provinsi.id).trigger('change');

                    var $newOption = $("<option selected='selected'></option>").val(res.kota.id).text(res.kota.nama)
                    $(lokasiInput).closest(".row").find(".kota_id").append($newOption).trigger('change');
    
                    if(res.kecamatan.id != null){
                        $newOption = $("<option selected='selected'></option>").val(res.kecamatan.id).text(res.kecamatan.nama)
                        $(lokasiInput).closest(".row").find(".kecamatan_id").append($newOption).trigger('change');
                    } else {
                        $(lokasiInput).closest(".row").find(".kecamatan_id").val(null).trigger('change');
                    }
    
                    if(res.kelurahan.id != null){
                        $newOption = $("<option selected='selected'></option>").val(res.kelurahan.id).text(res.kelurahan.nama)
                        $(lokasiInput).closest(".row").find(".kelurahan_id").append($newOption).trigger('change');
                    } else {
                        $(lokasiInput).closest(".row").find(".kelurahan_id").val(null).trigger('change');
                    }
                }
                
            }
        })
    });

    $('#pilihLokasi').on('shown.bs.modal', function() {
        map.resize();
    });
               
   
    let click = false
    $(document).on('click','.add_field_button',function(e) {
        click = true
        if(click){
            $(".input_fields_wrap").find("select").select2("destroy")
            let el = $("#clone").clone()
            $(".input_fields_wrap").append(el);
            el.addClass("mt-4")
            el.removeAttr('id')
            el.find('input').val('');
            el.find('textarea').val('')
            el.find('.action').html(`<button  class="remove_field btn btn-danger active" type="button">Delete</button>`)
            $(".input_fields_wrap").append(el);
            $(".input_fields_wrap").find("select.select2").select2();
            $(el).find("select").val(null).trigger('change');
            
            hubla()
            click = false
        }
        
        
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

    $(document).on("click", ".remove_field", function(e) {
        e.preventDefault();
        $(this).closest('.row').remove();
    })
}