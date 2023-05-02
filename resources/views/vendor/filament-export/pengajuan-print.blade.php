<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Filename</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma-rtl.min.css">
    {{-- <style type="text/css" media="all">
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }

        html {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border-radius: 10px 10px 10px 10px;
        }

        table td,
        th {
            border-color: #ededed;
            border-style: solid;
            border-width: 1px;
            font-size: 14px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        table th {
            font-weight: normal;
        }
    </style> --}}
</head>

<body>
    <div class="header">
        <div class="has-text-centered has-text-weight-bold">
            <p class="is-uppercase">formulir perjalanan dinas</p>
            <p class="is-uppercase">No. : sdp/dwadwa/dwada/daw</p>
        </div>
    </div>
    <div class="columns">
        <div class="column">
            First column
        </div>
        <div class="column">
            Second column
        </div>
        <div class="column">
            Third column
        </div>
        <div class="column">
            Fourth column
        </div>
    </div>
    {{-- <table>
        <tr>
            @foreach ($columns as $column)
                <th>
                    {{ $column->getLabel() }}
                </th>
            @endforeach
        </tr>
        @foreach ($rows as $row)
            <tr>
                @foreach ($columns as $column)
                    <td>
                        {{ $row[$column->getName()] }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>

    @if (isset($total))
        <p>Total : {{ $total }}</p>
    @endif --}}
</body>

</html>
