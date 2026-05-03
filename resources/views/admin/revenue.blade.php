@extends('layouts.admin')

@section('title', 'Отчёты о выручке')

@section('content')
<style>
    .revenue-container {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .revenue-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .summary-card {
        background: linear-gradient(135deg, #c94b8c 0%, #d96ba8 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
    }
    
    .summary-card h3 {
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .summary-card .value {
        font-size: 2rem;
        font-weight: 700;
    }
    
    .revenue-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 2rem;
    }
    
    .revenue-table th,
    .revenue-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .revenue-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }
    
    .revenue-table tr:hover {
        background-color: #f8f9fa;
    }
    
    .revenue-table tfoot td {
        font-weight: 700;
        font-size: 1.125rem;
        padding-top: 1.5rem;
        border-top: 2px solid #333;
    }
    
    .chart-container {
        margin-top: 2rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .bar-chart {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        height: 300px;
        gap: 0.5rem;
        padding: 1rem 0;
    }
    
    .bar {
        flex: 1;
        background: linear-gradient(to top, #c94b8c, #d96ba8);
        border-radius: 4px 4px 0 0;
        position: relative;
        min-height: 20px;
        transition: opacity 0.3s;
    }
    
    .bar:hover {
        opacity: 0.8;
    }
    
    .bar-label {
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.75rem;
        color: #666;
        white-space: nowrap;
    }
    
    .bar-value {
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.75rem;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
    }
    
    @media (max-width: 768px) {
        .revenue-table {
            font-size: 0.875rem;
        }
        
        .revenue-table th,
        .revenue-table td {
            padding: 0.5rem;
        }
        
        .bar-chart {
            height: 200px;
        }
        
        .bar-label {
            font-size: 0.625rem;
        }
    }
</style>

<h2 style="margin-bottom: 2rem;">Отчёты о выручке</h2>

<div class="revenue-container">
    @php
        $totalRevenue = collect($revenueData)->sum(function($item) {
            return (float) $item['total'];
        });
        $averageRevenue = count($revenueData) > 0 ? $totalRevenue / count($revenueData) : 0;
        $maxRevenue = collect($revenueData)->max(function($item) {
            return (float) $item['total'];
        }) ?? 0;
    @endphp
    
    <div class="revenue-summary">
        <div class="summary-card">
            <h3>Общая выручка (12 месяцев)</h3>
            <div class="value">{{ format_price($totalRevenue) }}</div>
        </div>
        
        <div class="summary-card">
            <h3>Среднее за месяц</h3>
            <div class="value">{{ format_price($averageRevenue) }}</div>
        </div>
        
        <div class="summary-card">
            <h3>Лучший месяц</h3>
            <div class="value">{{ format_price($maxRevenue) }}</div>
        </div>
    </div>
    
    <!-- Revenue Table -->
    <h3 style="margin-bottom: 1rem;">Детализация выручки по месяцам</h3>
    
    @if(count($revenueData) > 0)
        <table class="revenue-table">
            <thead>
                <tr>
                    <th>Месяц</th>
                    <th>Выручка</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueData as $data)
                    @php
                        $date = \Carbon\Carbon::parse($data['month'] . '-01');
                    @endphp
                    <tr>
                        <td>{{ $date->translatedFormat('F Y') }}</td>
                        <td>{{ format_price($data['total']) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Итого</td>
                    <td>{{ format_price($totalRevenue) }}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <p style="text-align: center; color: #666; padding: 2rem;">Нет данных о выручке за последние 12 месяцев.</p>
    @endif
</div>
@endsection
