<h1>Team Standings</h1>

@if($league->hasBracket == 1)
    @foreach($bracket as $division)
        <table class="standing" cellpadding="0" cellspacing="0" border="0">
            <thead>
                <tr>
                    <th style="font-size: 15pt;padding-left: 10pt;">{{$division->display_name}}</th>
                    <th>Wins</th>
                    <th>Losses</th>
                    @if(Auth::user())
                    <th width="5%">Action</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($division['team'] as $teams)
                    <tr>
                        <td class="league-team-name">{{$teams->display_name}}</td>
                        <td class="win-lose">{{$teams->wins}}</td>
                        <td class="win-lose">{{$teams->losses}}</td>
                        @if(Auth::user())
                            <td><a href="{{ route('standing.edit', $teams->team_id) }}" class="btn-flat">Edit</a></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br><br>
    @endforeach
    
@else
    <table class="standing" cellpadding="0" cellspacing="0" border="0">
        <thead>
            <tr>
                <th>Team Name</th>
                <th>Wins</th>
                <th>Losses</th>
                @if(Auth::user())
                    <th width="5%">Action</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach ($leagueStanding as $stand)
            <tr>
                <td class="league-team-name">{{$stand->display_name}}</td>
                <td class="win-lose">{{$stand->wins}}</td>
                <td class="win-lose">{{$stand->losses}}</td>
                @if(Auth::user())
                <td><a href="{{ route('standing.edit', $stand->team_id) }}" class="btn-flat">Edit</a></td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
@endif