<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add books to the cart.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        $user = Auth::user();
        $cartItems = [];

        $books = $request->input('books');

        foreach ($books as $book) {
            $bookId = $book['book_id'];
            $quantity = $book['amount'] ?? 1;

            $existingCart = Cart::where('user_id', $user->id)
                ->where('book_id', $bookId)
                ->first();

            if ($existingCart) {
                $existingCart->amount += $quantity;
                $existingCart->save();
                $cartItems[] = $existingCart;
            } else {
                $book = Book::find($bookId);

                if ($book) {
                    $cart = new Cart();
                    $cart->user_id = $user->id;
                    $cart->book_id = $bookId;
                    $cart->amount = $quantity;
                    $cart->price = $book->price;
                    $cart->save();
                    $cartItems[] = $cart;
                }
            }
        }

        return response()->json(['message' => 'Books added to cart', 'cart_items' => $cartItems]);
    }

    /**
     * Remove a book from the cart.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromCart(Request $request)
    {
        $user = Auth::user();
        $bookId = $request->input('book_id');
        $amount = $request->input('amount') ?? 1;
    
        $cart = Cart::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->first();
    
        if ($cart) {
            if ($amount >= $cart->amount) {
                $cart->delete();
            } else {
                $cart->amount -= $amount;
                $cart->save();
            }
    
            return response()->json(['message' => 'Book(s) removed from cart', "cart" => $cart]);
        }
    
        return response()->json(['message' => 'Book not found in cart'], 404);
    }
    

    /**
     * Get the cart items for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartItems()
    {
        $user = Auth::user();

        $cartItems = Cart::where('user_id', $user->id)->with('book')->get();

        $totalPrice = $cartItems->sum(function ($cartItem) {
            return $cartItem->price * $cartItem->amount;
        });

        return response()->json(['cart_items' => $cartItems, 'total_price' => $totalPrice]);
    }

    /**
     * Clear the cart for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCart()
    {
        $user = Auth::user();

        Cart::where('user_id', $user->id)->delete();

        return response()->json(['message' => 'Cart cleared']);
    }
}
