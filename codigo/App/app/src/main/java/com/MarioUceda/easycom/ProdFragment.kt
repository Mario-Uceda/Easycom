package com.MarioUceda.easycom

import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import com.MarioUceda.easycom.databinding.FragmentProdBinding
import com.bumptech.glide.Glide


class ProdFragment : Fragment(R.layout.fragment_fav) {
    private var _binding: FragmentProdBinding? = null
    private val binding get() = _binding!!

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentProdBinding.inflate(inflater, container, false)
        return binding.root
    }
    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        val url =
            "https://cdn.computerhoy.com/sites/navi.axelspringer.es/public/media/image/2020/11/analisis-ps5-computerhoy-2140439.jpg?tf=3840x"
        Glide.with(this)
            .load(url)
            .into(binding.prodImg)
    }
}