<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'first_name' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
        ]);

        $cartItems = $user->cartItems()->with('article')->get();
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($cartItems as $it) {
                $total += ($it->article->price ?? 0) * $it->quantity;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'status' => Order::STATUS_PENDING,
            ]);

            foreach ($cartItems as $it) {
                $order->items()->create([
                    'article_id' => $it->article_id,
                    'quantity' => $it->quantity,
                    'unit_price' => $it->article->price ?? 0,
                ]);

                if (isset($it->article->stock) && is_numeric($it->article->stock)) {
                    $it->article->decrement('stock', $it->quantity);
                }
            }

            $user->cartItems()->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Erreur lors de la création de la commande.');
        }

        return redirect()->route('orders.show', $order)->with('success', 'Commande créée.');
    }

    public function show(Order $order)
    {
        $order->load('items.article', 'user');
        return view('orders.show', compact('order'));
    }
}
