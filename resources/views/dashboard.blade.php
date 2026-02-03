<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Cantine</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-100 text-slate-900">
    <div class="min-h-screen px-4 py-6 sm:px-6 lg:px-10">
        <header class="mb-6 flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm uppercase tracking-wide text-slate-400">Année active</p>
                <h1 class="text-2xl font-semibold text-slate-800">{{ $schoolYear->code }}</h1>
                <p class="text-sm text-slate-500">Mois courant : {{ $month }} ({{ ucfirst($monthLabel) }})</p>
            </div>
            <div class="flex items-center gap-3 rounded-xl bg-slate-50 px-4 py-3 text-slate-600">
                <div>
                    <p class="text-xs uppercase tracking-wide">Date &amp; heure</p>
                    <p class="text-lg font-semibold text-slate-800">{{ $now->translatedFormat('l d F Y') }}</p>
                </div>
                <span class="text-lg font-semibold">{{ $now->format('H:i') }}</span>
            </div>
        </header>

        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total élèves actifs</p>
                <p class="mt-2 text-3xl font-semibold">{{ $totalStudents }}</p>
                <p class="mt-3 text-xs text-slate-400">Tous niveaux confondus</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Encaissement du mois</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($totalCollected, 0, ',', ' ') }} FCFA</p>
                <p class="mt-3 text-xs text-emerald-500">Mois {{ $month }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Élèves à jour</p>
                <p class="mt-2 text-3xl font-semibold">{{ $totalPaid }}</p>
                <p class="mt-3 text-xs text-slate-400">Paiement complet ou remise</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Impayés</p>
                <p class="mt-2 text-3xl font-semibold">{{ $totalUnpaid }}</p>
                <p class="mt-3 text-xs text-rose-500">Aucun versement</p>
            </div>
        </section>

        <section class="mt-6 grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl bg-white p-5 shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Activité récente</h2>
                    <span class="text-sm text-slate-400">10 derniers paiements</span>
                </div>
                <div class="mt-4 space-y-4">
                    @forelse($recentPayments as $payment)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ $payment->student?->full_name ?? 'Élève' }}</p>
                                <p class="text-xs text-slate-500">Classe : {{ $payment->student?->classroom ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold">{{ number_format($payment->amount_paid, 0, ',', ' ') }} FCFA</p>
                                <p class="text-xs text-slate-500">{{ optional($payment->paid_at)->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-slate-400">{{ $payment->method }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aucun paiement enregistré pour le moment.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">Actions rapides</h2>
                <div class="mt-4 grid gap-3">
                    <a href="/api/payments" class="flex items-center justify-center rounded-xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-white shadow-sm">Encaisser</a>
                    <a href="/api/students" class="flex items-center justify-center rounded-xl bg-indigo-500 px-4 py-3 text-sm font-semibold text-white shadow-sm">Liste élèves</a>
                    <a href="/api/students?status=unpaid" class="flex items-center justify-center rounded-xl bg-rose-500 px-4 py-3 text-sm font-semibold text-white shadow-sm">Impayés</a>
                    <a href="/api/settings" class="flex items-center justify-center rounded-xl bg-slate-800 px-4 py-3 text-sm font-semibold text-white shadow-sm">Paramètres</a>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
