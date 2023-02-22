package com.MarioUceda.easycom

import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import com.MarioUceda.easycom.databinding.FragmentNotiBinding


class NotiFragment : Fragment() {
    private var _binding: FragmentNotiBinding? = null
    private val binding get() = _binding!!

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentNotiBinding.inflate(inflater, container, false)
        return binding.root
    }

}