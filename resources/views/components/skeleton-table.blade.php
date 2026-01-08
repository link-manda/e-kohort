<div class="animate-pulse">
    <div class="space-y-3">
        @for ($i = 0; $i < $rows; $i++)
            <div class="flex space-x-4">
                @for ($j = 0; $j < $columns; $j++)
                    <div class="flex-1 h-4 bg-gray-200 rounded"></div>
                @endfor
            </div>
        @endfor
    </div>
</div>
