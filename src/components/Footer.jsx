import React from 'react';

const Footer = () => {
  return (
    <footer className="bg-black text-white mt-20">
      <div className="container mx-auto px-4 py-8">
        <div className="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
          {/* Left Side - Copyright */}
          <div className="text-gray-400 text-sm">
            Order.cm ©Copyright 2025, All Rights Reserved.
          </div>

          {/* Center - Logo */}
          <div className="flex items-center">
            <div className="w-15 h-15 bg-black rounded-full flex items-center justify-center">
              <div className="text-center">
                <img src="./public/image 11.svg" alt="logo"></img>
            
              </div>
            </div>
          </div>

          {/* Right Side - Links */}
          <div className="flex items-center space-x-6">
            <a 
              href="#privacy" 
              className="text-gray-400 hover:text-white transition-colors text-sm"
            >
              Privacy Policy
            </a>
            <a 
              href="#terms" 
              className="text-gray-400 hover:text-white transition-colors text-sm"
            >
              Terms
            </a>
            <a 
              href="#pricing" 
              className="text-gray-400 hover:text-white transition-colors text-sm"
            >
              Pricing
            </a>
            <a 
              href="#privacy-info" 
              className="text-gray-400 hover:text-white transition-colors text-sm"
            >
              Do not share your personal information
            </a>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;