//
//  ViewController.m
//  TestApplication
//
//  Created by Sarah Mehmedi on 10/17/15.
//  Copyright © 2015 Sarah Mehmedi. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    NSString *webSite = @"http://ec2-107-21-138-228.compute-1.amazonaws.com";
    NSURL  *url = [NSURL URLWithString:webSite];
    NSURLRequest *request = [NSURLRequest requestWithURL:url];
    
    [webView loadRequest:request];
    
}
-(void)webView:(UIWebView *)webView didFailLoadWithError:(nullable NSError *)error;
{
    NSLog(@"Error : %@", error);
}

- (void)didReceiveMemoryWarning {
    
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
