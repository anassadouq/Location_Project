@extends('layouts.app')
@section('content')
    <html>
        <head>
            <title>Reservations</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.4/datatables.min.css"/>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        </head>
        <body>
            <div class="container">
                <h1 class="text-center">RESERVATIONS</h1>

                <a href="{{route('reservation.create')}}" >
                    <button class="btn btn-primary my-3" style="width:85px">
                        <span class="material-symbols-outlined">add</span>
                    </button>
                </a>
                <table class="text-center" width="100%" id="myTable">
                    <thead style="background-color:#B0B0B0">
                        <tr>
                            <th>Client</th>
                            <th>Téléphone</th>
                            <th>Voiture</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Durée</th>
                            <th>Montant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->client->prenom }} {{ $reservation->client->nom }}</td>
                            <td>{{ $reservation->client->tel }}</td>
                            <td>{{ $reservation->voiture->marque }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->dateD)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->dateF)->format('d/m/Y') }}</td>
                            <td>
                            {{
                                \Carbon\Carbon::parse($reservation->dateD)
                                ->diffInDays(\Carbon\Carbon::parse($reservation->dateF)) +1
                                . ' jours'
                            }}
                            </td>
                            <td>{{ $reservation->voiture->prixJ * (\Carbon\Carbon::parse($reservation->dateD)
                                ->diffInDays(\Carbon\Carbon::parse($reservation->dateF)) +1) }} DH
                            </td>
                            <td>
                                <form action="{{ route('reservation.destroy', $reservation->id) }}" method="POST" id="deleteForm{{ $reservation->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <ul class="navbar-nav ms-auto">
                                        <li class="nav-item dropdown">
                                            <a id="navbarDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                            </svg>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                                <a href="{{ route('reservation.edit', $reservation->id) }}" class="btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                    </svg>
                                                    &nbsp;Editer
                                                </a>

                                                <button type="button" onclick="confirmDelete('{{ $reservation->id }}')" class="btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M10.354 8l3.823-3.823a.5.5 0 0 0-.708-.708L9.646 7.293 5.823 3.469a.5.5 0 0 0-.708.708L8.293 8l-3.82 3.823a.5.5 0 1 0 .708.708L9.647 8.707l3.824 3.824a.5.5 0 0 0 .708-.708L10.354 8z"/>
                                                    </svg>
                                                    &nbsp;Supprimer
                                                </button>

                                                <a href="{{ route('reservation.pdf', ['clientId' => $reservation->client->id]) }}" class="btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                                    </svg>
                                                    &nbsp;Télécharger
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>    
            </div>
            
            <script>
                function confirmDelete(reservationId) {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette reservation ?')) {
                        document.getElementById('deleteForm' + reservationId).submit();
                    }
                }
            </script>

            <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
            <script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>

            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable( {
                        dom: 'Blfrtip',
                        lengthChange: false, // disable length change dropdown
                        paging: false, // disable pagination
                        ordering: false, // disable sorting for all columns
                        buttons: [{
                            extend: 'collection',
                            text: 'Export',
                            buttons: [{
                                extend: 'excelHtml5',
                                exportOptions: {
                                    columns: [ 0,1,2,3,4 ]
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                exportOptions: {
                                    columns: [ 0,1,2,3,4 ]
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                exportOptions: {
                                    columns: [ 0,1,2,3,4 ]
                                },
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [ 0,1,2,3,4 ]
                                }
                            }],
                        }]
                    });
                });
            </script>
        </body=>
    </html>
@endsection