package com.MarioUceda.easycom

import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import com.MarioUceda.easycom.databinding.FragmentProdBinding


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

}