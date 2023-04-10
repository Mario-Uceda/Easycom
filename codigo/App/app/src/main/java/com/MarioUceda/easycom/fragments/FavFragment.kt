package com.MarioUceda.easycom.fragments

import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.LinearLayoutManager
import com.MarioUceda.easycom.classes.Precio
import com.MarioUceda.easycom.classes.Producto
import com.MarioUceda.easycom.R
import com.MarioUceda.easycom.databinding.FragmentFavBinding

class FavFragment : Fragment() {
    private var _binding: FragmentFavBinding? = null
    private val binding get() = _binding!!
    private lateinit var productos: ArrayList<Producto>
    private lateinit var precios: ArrayList<Precio>
    private lateinit var productAdapter: ProductListAdapter
    private var selectedProductIndex: Int = -1

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentFavBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        // Initialize products array
        productos = ArrayList()
        productos.add(Producto("1","12345","Playstation 5", "https://www.amazon.es/Playstation-Consola-PlayStation-5/dp/B08KKJ37F7/ref=sxts_rp_s_1_0?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&content-id=amzn1.sym.fed3ea6f-2dfb-4b4a-84b5-d636374520f9%3Aamzn1.sym.fed3ea6f-2dfb-4b4a-84b5-d636374520f9&crid=G4HR6STH09BZ&cv_ct_cx=ps5&keywords=ps5&pd_rd_i=B08KKJ37F7&pd_rd_r=81fd500d-ce60-4200-8770-9c678be83d8a&pd_rd_w=uduUP&pd_rd_wg=vYlLQ&pf_rd_p=fed3ea6f-2dfb-4b4a-84b5-d636374520f9&pf_rd_r=MN7FXGGZK6DBNWXV7613&qid=1679686561&sbo=RZvfv%2F%2FHxDF%2BO5021pAnSA%3D%3D&sprefix=ps5%2Caps%2C92&sr=1-1-5ee58b93-78b0-4afa-82f4-4348ec6d11fa", "https://cdn.idealo.com/folder/producto/201989/3/201989322/s11_produktbild_gross_1/sony-playstation-5-ps5-horizon-forbidden-west.jpg", "Description 1","www-amazon.es"))
        productos.add(Producto("2","12345","producto 2", "", "https://pbs.twimg.com/profile_images/1610557276732940288/DeKynRLF_400x400.jpg", "Description 2","www-amazon.es"))
        productos.add(Producto("3","12345", "producto 3", "", "https://images.daznservices.com/di/library/DAZN_News/7c/f6/2023-03-18-alonso-f1-formula-1_lh774xonqgqs1j5qh3eaii55p.jpg?t=-167016602", "Description 3","www-amazon.es"))
        productos.add(Producto("4","12345", "producto 4", "", "https://cdn-2.motorsport.com/images/mgl/6AEomeD6/s800/fernando-alonso-aston-martin-r-1.jpg", "Description 4","www-amazon.es"))
        productos.add(Producto("5","12345","producto 5", "", "https://imagenes.20minutos.es/files/image_990_v3/uploads/imagenes/2023/03/15/fernando-alonso-ya-esta-en-jeddah.jpeg", "Description 5","www-amazon.es"))
        productos.add(Producto("6","12345", "producto 6", "", "https://pbs.twimg.com/profile_images/1610557276732940288/DeKynRLF_400x400.jpg", "Description 6","www-amazon.es"))
        productos.add(Producto("7","12345","producto 7", "", "https://images.daznservices.com/di/library/DAZN_News/7c/f6/2023-03-18-alonso-f1-formula-1_lh774xonqgqs1j5qh3eaii55p.jpg?t=-167016602", "Description 7","www-amazon.es"))
        productos.add(Producto("8","12345","producto 8", "", "https://cdn-2.motorsport.com/images/mgl/6AEomeD6/s800/fernando-alonso-aston-martin-r-1.jpg", "Description 8","www-amazon.es"))
        productos.add(Producto("9","12345","producto 9", "", "https://imagenes.20minutos.es/files/image_990_v3/uploads/imagenes/2023/03/15/fernando-alonso-ya-esta-en-jeddah.jpeg", "Description 9","www-amazon.es"))
        productos.add(Producto("10","12345", "producto 10", "", "https://pbs.twimg.com/profile_images/1610557276732940288/DeKynRLF_400x400.jpg", "Description 10","www-amazon.es"))
        productos.add(Producto("11","12345","producto 11", "", "https://images.daznservices.com/di/library/DAZN_News/7c/f6/2023-03-18-alonso-f1-formula-1_lh774xonqgqs1j5qh3eaii55p.jpg?t=-167016602", "Description 11","www-amazon.es"))
        productos.add(Producto("12","12345","producto 12", "", "https://cdn-2.motorsport.com/images/mgl/6AEomeD6/s800/fernando-alonso-aston-martin-r-1.jpg", "Description 12","www-amazon.es"))
        productos.add(Producto("13","12345", "producto 13", "", "https://imagenes.20minutos.es/files/image_990_v3/uploads/imagenes/2023/03/15/fernando-alonso-ya-esta-en-jeddah.jpeg", "Description 13","www-amazon.es"))
        productos.add(Producto("14","12345", "producto 14", "", "https://pbs.twimg.com/profile_images/1610557276732940288/DeKynRLF_400x400.jpg", "Description 14","www-amazon.es"))
        productos.add(Producto("15","12345","producto 15", "", "https://images.daznservices.com/di/library/DAZN_News/7c/f6/2023-03-18-alonso-f1-formula-1_lh774xonqgqs1j5qh3eaii55p.jpg?t=-167016602", "Description 15","www-amazon.es"))
        productos.add(Producto("16","12345","producto 16", "", "https://cdn-2.motorsport.com/images/mgl/6AEomeD6/s800/fernando-alonso-aston-martin-r-1.jpg", "Description 16","www-amazon.es"))


        // Initialize prices array
        precios = ArrayList()
        precios.add(Precio("1", "1", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("2", "2", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("3", "3", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("4", "4", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("5", "5", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("6", "6", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("7", "7", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("8", "8", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("9", "9", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("10", "10", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("11", "11", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("12", "12", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("13", "13", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("14", "14", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("15", "15", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        precios.add(Precio("16", "16", 499.99,"Amazon","www-amazon.es","03-04-2023"))
        

        val layoutManager = LinearLayoutManager(context)
        productAdapter = ProductListAdapter(productos) { position ->
            selectedProductIndex = position
            onItemClick()
        }


        // Set up RecyclerView

        binding.productRecyclerView.layoutManager = layoutManager
        binding.productRecyclerView.adapter = productAdapter
    }

    private fun onItemClick() {
        if (selectedProductIndex >= 0 && selectedProductIndex < productos.size) {
            val selectedProduct = productos[selectedProductIndex]
            cambiarFragmento(selectedProductIndex)

        }
    }

    private fun cambiarFragmento(seleccionado: Int) {
        var prodFragment = ProdFragment()
        val bundle = Bundle().apply {
            putSerializable("producto", productos[seleccionado])
            putSerializable("precio", precios[seleccionado] )
        }
        prodFragment.arguments = bundle

        //cambiar al fragmento de producto
        requireActivity().supportFragmentManager.beginTransaction().apply {
            replace(R.id.containerView, prodFragment)
            commit()
        }
    }

}