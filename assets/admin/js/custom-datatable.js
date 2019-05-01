(function ($) {
    $(document).ready(function() {

        $(".chngOrderStatus").click(function(e){
            e.preventDefault();

            let id = $(this).data('id');
            let type = $(this).data('type');
            let action = $(this).data('action');
            let url = $(this).data('url');

            console.log("Id: "+id+", Type: "+type+", Action: "+action+", URL: "+url);

            $.ajax({
                url: url,
                type: "POST",
                datatype: "json",
                data: {id: id, status: action, type: type},
                success: function(resp){
                    console.log("Response: ",resp);
                },
                error: function(error){
                    console.log("Error: ",error);
                }
            });
        });

        var salesDataTable = $('#ks-sales-datatable').DataTable({
         //   dom: 'rtip',
			searching: true,
            pageLength: 20,
            "bSort": false,
            responsive: {
                details: false
            }
        });
		
/*		$('#ks-sales-datatable').DataTable({
            "initComplete": function () {
                $('.dataTables_wrapper select').select2({
                    minimumResultsForSearch: Infinity
                });
            }
        });*/
        $('.dataTables_wrapper').css('width', $('.dataTables_wrapper').closest('.panel').width() + 10);
        salesDataTable.columns.adjust().draw();

        $('.swiper-container').width($('.swiper-container').closest('.ks-slider').width());

        var swiper = new Swiper ('.swiper-container', {
            paginationClickable: true,
            slidesPerView: 5,
            spaceBetween: 20,
            pagination: '.swiper-pagination',
            breakpoints: {
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 40
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10
                }
            }

            /*// Navigation arrows
             nextButton: '.swiper-button-next',
             prevButton: '.swiper-button-prev'*/
        })
    });
})(jQuery);