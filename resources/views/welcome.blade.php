<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset("css/bootstrap.min.css") }}">
    <title>Laravel</title>


</head>
<body>
<div class="container">
    <div class="card mt-5">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <select class="form-control filter country_select" name="country_id">
                        <option value="">All Countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control filter is_valid" name="is_valid">
                        <option value="">All Statuses</option>
                        <option value="yes">OK</option>
                        <option value="no">NOK</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>Country</td>
                    <td>State</td>
                    <td>Country Code</td>
                    <td>Phone Number</td>
                </tr>
                </thead>
                <tbody class="tbody">

                </tbody>
            </table>


            <button class="prev"><< Prev</button>
            <button class="next">Next >></button>
        </div>
    </div>
</div>
</body>
</html>


<script src="{{ asset("js/jquery.min.js") }}"></script>

<script>

    const phone_table_hr = ({row}) => {
        return `<tr>
                    <td>${row?.country_name}</td>
                    <td>${row?.state == '1' ? "OK" : "NOK"}</td>
                    <td>${row?.country_code}</td>
                    <td>${row?.phone}</td>
                </tr>`;
    };


    const load_table = ({get_url}) => {
        let url = "{{ route('api.phones.index') }}";
        if (get_url) url = get_url;

        $.get(url,
            {
                country_id: $('.country_select').find(":selected").val(),
                is_valid: $('.is_valid').find(":selected").val()
            }
        ).done((data) => {
            console.log(data)
            $(".tbody").children().remove();
            const phones = data?.phones?.data ?? [];
            phones.forEach(phone => {
                const tr = phone_table_hr({row: phone});
                $(".tbody").append(tr);
            });

            if (!data.phones?.links?.next) {
                $(".next").hide();
            } else {
                $(".next").show();
                $('.next').attr("data-url", data.phones?.links?.next);
            }
            console.log(data.phones?.links?.prev)
            if (!data.phones?.links?.prev) {
                $(".prev").hide();
            }else{
                $(".prev").show();
                $('.prev').attr("data-url", data.phones?.links?.prev);

        });
    };

    load_table({});


    $('.filter').change(function () {
        load_table({});
    });

    $(".next , .prev").click(function () {
        load_table({get_url:$(this).attr('data-url')});
    })

</script>
