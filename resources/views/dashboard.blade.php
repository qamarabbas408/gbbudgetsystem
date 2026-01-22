@extends('layouts.app')

@section('title', 'Executive Dashboard')

@section('content')
<div class="max-w-[1920px] mx-auto space-y-8 animate-fade-in-up">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Overview</h1>
            <p class="text-gray-500 mt-1">Real-time insights into ADP 2025-26 performance.</p>
        </div>
        <div class="flex gap-3">
             <button class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 shadow-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Report
            </button>
            <button class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                New Scheme
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Card 1: Total Allocation -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xl shadow-gray-100/50 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-purple-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center mb-4 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Allocation</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">₨ 45.2<span class="text-lg text-gray-500 ml-1">B</span></h3>
                <div class="flex items-center gap-2 mt-3 text-sm">
                    <span class="text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-md flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        +12%
                    </span>
                    <span class="text-gray-400">vs last year</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Releases -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xl shadow-gray-100/50 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
             <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mb-4 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Releases</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">₨ 28.4<span class="text-lg text-gray-500 ml-1">B</span></h3>
                 <div class="w-full bg-gray-100 h-1.5 rounded-full mt-4 overflow-hidden">
                    <div class="bg-blue-500 h-full rounded-full" style="width: 62%"></div>
                </div>
                 <p class="text-xs text-gray-400 mt-2 font-medium">62% of allocation released</p>
            </div>
        </div>

        <!-- Card 3: Expenditure -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xl shadow-gray-100/50 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
             <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-teal-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center mb-4 text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Expenditure</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">₨ 15.1<span class="text-lg text-gray-500 ml-1">B</span></h3>
                 <div class="w-full bg-gray-100 h-1.5 rounded-full mt-4 overflow-hidden">
                    <div class="bg-teal-500 h-full rounded-full" style="width: 53%"></div>
                </div>
                 <p class="text-xs text-gray-400 mt-2 font-medium">53% of releases utilized</p>
            </div>
        </div>

         <!-- Card 4: Schemes -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xl shadow-gray-100/50 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
             <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
               <svg class="w-24 h-24 text-orange-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center mb-4 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Schemes</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">1,421</h3>
                 <div class="flex items-center gap-2 mt-4">
                     <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-1 rounded">945 Approved</span>
                     <span class="text-xs font-bold text-red-700 bg-red-100 px-2 py-1 rounded">476 Un-Appr.</span>
                 </div>
            </div>
        </div>
    </div>

    <!-- Second Row: Analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sector Breakdown -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-100/50">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900">Sector-wise Allocation</h3>
                <button class="text-sm text-blue-600 font-semibold hover:text-blue-800">View Details</button>
            </div>
            
            <div class="space-y-6">
                <!-- Infastructure -->
                <div class="group">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-700 group-hover:text-blue-700 transition-colors">Infrastructure (Roads & Bridges)</span>
                        <span class="font-bold text-gray-900">₨ 18.5B (41%)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full" style="width: 41%"></div>
                    </div>
                </div>

                 <!-- Energy -->
                 <div class="group">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-700 group-hover:text-yellow-600 transition-colors">Power & Energy</span>
                         <span class="font-bold text-gray-900">₨ 8.2B (18%)</span>
                    </div>
                     <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-full rounded-full" style="width: 18%"></div>
                    </div>
                </div>

                <!-- Education -->
                 <div class="group">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-700 group-hover:text-green-600 transition-colors">Education & Schools</span>
                         <span class="font-bold text-gray-900">₨ 6.4B (14%)</span>
                    </div>
                     <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-400 to-green-500 h-full rounded-full" style="width: 14%"></div>
                    </div>
                </div>
                 <!-- Health -->
                 <div class="group">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-700 group-hover:text-red-500 transition-colors">Health & Hospital</span>
                         <span class="font-bold text-gray-900">₨ 5.9B (13%)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-400 to-red-500 h-full rounded-full" style="width: 13%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- District Performance -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-100/50">
             <h3 class="text-lg font-bold text-gray-900 mb-6">District Utilization</h3>
             <div class="space-y-4">
                 <!-- Item -->
                 <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-2xl transition-all cursor-pointer">
                     <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-xs">SKD</div>
                     <div class="flex-1">
                         <h4 class="text-sm font-bold text-gray-900">Skardu</h4>
                         <p class="text-xs text-gray-500">72 schemes</p>
                     </div>
                     <div class="text-right">
                         <div class="text-sm font-bold text-green-600">88%</div>
                         <div class="text-[10px] text-gray-400">Utilized</div>
                     </div>
                 </div>
                  <!-- Item -->
                 <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-2xl transition-all cursor-pointer">
                     <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center font-bold text-purple-700 text-xs">GLT</div>
                     <div class="flex-1">
                         <h4 class="text-sm font-bold text-gray-900">Gilgit</h4>
                         <p class="text-xs text-gray-500">145 schemes</p>
                     </div>
                     <div class="text-right">
                         <div class="text-sm font-bold text-blue-600">64%</div>
                          <div class="text-[10px] text-gray-400">Utilized</div>
                     </div>
                 </div>
                  <!-- Item -->
                 <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-2xl transition-all cursor-pointer">
                     <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center font-bold text-orange-700 text-xs">DM</div>
                     <div class="flex-1">
                         <h4 class="text-sm font-bold text-gray-900">Diamer</h4>
                         <p class="text-xs text-gray-500">58 schemes</p>
                     </div>
                     <div class="text-right">
                         <div class="text-sm font-bold text-orange-600">45%</div>
                          <div class="text-[10px] text-gray-400">Utilized</div>
                     </div>
                 </div>
                  <!-- Item -->
                 <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-2xl transition-all cursor-pointer">
                     <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center font-bold text-teal-700 text-xs">HZA</div>
                     <div class="flex-1">
                         <h4 class="text-sm font-bold text-gray-900">Hunza</h4>
                         <p class="text-xs text-gray-500">34 schemes</p>
                     </div>
                     <div class="text-right">
                         <div class="text-sm font-bold text-green-600">92%</div>
                          <div class="text-[10px] text-gray-400">Utilized</div>
                     </div>
                 </div>
             </div>
             <button class="w-full mt-6 py-3 border-2 border-dashed border-gray-200 rounded-xl text-sm font-semibold text-gray-500 hover:border-blue-300 hover:text-blue-600 transition-all">
                 View All Districts
             </button>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-100/50">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900">Recent System Activity</h3>
             <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full">Real-time Feed</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs text-gray-500 uppercase border-b border-gray-100">
                        <th class="pb-3 pl-2">Activity</th>
                        <th class="pb-3">User</th>
                        <th class="pb-3">Time</th>
                        <th class="pb-3 text-right pr-2">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr class="group hover:bg-gray-50 transition-colors">
                        <td class="py-4 pl-2 border-b border-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">SAP Dump Uploaded</p>
                                    <p class="text-xs text-gray-500">Batch #2026-01-22-A imported 4,102 rows</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 border-b border-gray-50 text-gray-600">Admin User</td>
                        <td class="py-4 border-b border-gray-50 text-gray-400 font-mono text-xs">10 mins ago</td>
                        <td class="py-4 border-b border-gray-50 text-right pr-2">
                             <span class="px-2 py-1 bg-green-50 text-green-600 rounded text-xs font-bold">Success</span>
                        </td>
                    </tr>
                    <tr class="group hover:bg-gray-50 transition-colors">
                        <td class="py-4 pl-2 border-b border-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Scheme Approved</p>
                                    <p class="text-xs text-gray-500">Upgradation of DHQ Ghizer</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 border-b border-gray-50 text-gray-600">Sec. PND</td>
                        <td class="py-4 border-b border-gray-50 text-gray-400 font-mono text-xs">2 hours ago</td>
                         <td class="py-4 border-b border-gray-50 text-right pr-2">
                             <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs font-bold">Approved</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }
</style>
@endsection