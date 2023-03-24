package com.MarioUceda.easycom

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import androidx.recyclerview.widget.LinearLayoutManager
import com.MarioUceda.easycom.databinding.FragmentProductListBinding

class ProductListFragment : Fragment() {

    private lateinit var binding: FragmentProductListBinding
    private lateinit var products: ArrayList<Product>
    private lateinit var prices: ArrayList<Price>
    private lateinit var productAdapter: ProductListAdapter
    private var selectedProductIndex: Int = -1

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentProductListBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        // Initialize products array
        products = ArrayList()
        products.add(Product("1", "Playstation 5", "https://www.amazon.es/Playstation-Consola-PlayStation-5/dp/B08KKJ37F7/ref=sxts_rp_s_1_0?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&content-id=amzn1.sym.fed3ea6f-2dfb-4b4a-84b5-d636374520f9%3Aamzn1.sym.fed3ea6f-2dfb-4b4a-84b5-d636374520f9&crid=G4HR6STH09BZ&cv_ct_cx=ps5&keywords=ps5&pd_rd_i=B08KKJ37F7&pd_rd_r=81fd500d-ce60-4200-8770-9c678be83d8a&pd_rd_w=uduUP&pd_rd_wg=vYlLQ&pf_rd_p=fed3ea6f-2dfb-4b4a-84b5-d636374520f9&pf_rd_r=MN7FXGGZK6DBNWXV7613&qid=1679686561&sbo=RZvfv%2F%2FHxDF%2BO5021pAnSA%3D%3D&sprefix=ps5%2Caps%2C92&sr=1-1-5ee58b93-78b0-4afa-82f4-4348ec6d11fa", "https://cdn.idealo.com/folder/Product/201989/3/201989322/s11_produktbild_gross_1/sony-playstation-5-ps5-horizon-forbidden-west.jpg", "Description 1"))
        products.add(Product("2", "Product 2", "", "https://pbs.twimg.com/profile_images/1610557276732940288/DeKynRLF_400x400.jpg", "Description 2"))
        products.add(Product("3", "Product 3", "", "https://images.daznservices.com/di/library/DAZN_News/7c/f6/2023-03-18-alonso-f1-formula-1_lh774xonqgqs1j5qh3eaii55p.jpg?t=-167016602", "Description 3"))
        products.add(Product("4", "Product 4", "", "https://cdn-2.motorsport.com/images/mgl/6AEomeD6/s800/fernando-alonso-aston-martin-r-1.jpg", "Description 4"))
        products.add(Product("5", "Product 5", "", "https://imagenes.20minutos.es/files/image_990_v3/uploads/imagenes/2023/03/15/fernando-alonso-ya-esta-en-jeddah.jpeg", "Description 5"))
        // Initialize prices array
        prices = ArrayList()
        prices.add(Price("Amazon", "1", "499.99"))
        prices.add(Price("Amazon", "2", "499.99"))
        prices.add(Price("Amazon", "3", "499.99"))
        prices.add(Price("Amazon", "4", "499.99"))
        prices.add(Price("Amazon", "5", "499.99"))

        val layoutManager = LinearLayoutManager(context)
        productAdapter = ProductListAdapter(products) { position ->
            selectedProductIndex = position
            onItemClick()
        }


        // Set up RecyclerView

        binding.productRecyclerView.layoutManager = layoutManager
        binding.productRecyclerView.adapter = productAdapter
    }

    private fun onItemClick() {
        if (selectedProductIndex >= 0 && selectedProductIndex < products.size) {
            val selectedProduct = products[selectedProductIndex]
            println("Selected product id: ${selectedProduct.id}")
        }
    }

}